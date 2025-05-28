<?php

declare(strict_types=1);

namespace Gmbit\Simple\Controller;

use Gmbit\Simple\Domain\Model\Member;
use Gmbit\Simple\Domain\Repository\MemberRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

class MemberController extends ActionController
{
    private const STORAGE_PID = 15;

    public function __construct(
        protected readonly MemberRepository $memberRepository,
        protected readonly Context $context,
        protected readonly LoggerInterface $logger,
        protected readonly SiteFinder $siteFinder,
        protected readonly PersistenceManagerInterface $persistenceManager
    ) {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setStoragePageIds([self::STORAGE_PID]);
        $querySettings->setRespectStoragePage(true);
        $querySettings->setRespectSysLanguage(true);
        $this->memberRepository->setDefaultQuerySettings($querySettings);
    }

    public function initializeAction(): void
    {
        $this->settings['persistence']['storagePid'] = self::STORAGE_PID;
        $this->logRequestParams();
    }

    public function indexAction(): ResponseInterface
    {
        $members = $this->memberRepository->findAll();
        $enabledLanguages = $this->getEnabledLanguages();
        $currentPageId = (int)($this->request->getArgument('id') ?? self::STORAGE_PID);

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $translations = $this->memberRepository->findAllTranslationsIndexed();

        $languageLinks = [];

        foreach ($members as $member) {
            $languageMenu = [];

            foreach ($enabledLanguages as $languageId => $languageConf) {
                if ($languageId === 0) {
                    continue;
                }

                $translation = $translations[$member->getUid()][$languageId] ?? null;

                if ($translation !== null) {
                    $link = $uriBuilder->buildUriFromRoute('record_edit', [
                        'edit[tx_simple_domain_model_member][' . $translation->getUid() . ']' => 'edit',
                        'returnUrl' => (string)$this->uriBuilder->reset()->uriFor('index')
                    ]);
                } else {
$uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

$link = $uriBuilder->buildUriFromRoute(
    'record_edit',
    [
        'edit' => [
            'tx_simple_domain_model_member' => [
                $member->getPid() => 'new', // PID kao ključ
            ],
        ],
        'defVals' => [
            'tx_simple_domain_model_member' => [
                'sys_language_uid' => 1,
                'l10n_parent' => $member->getUid(),
            ],
        ],
        'returnUrl' => (string)$this->uriBuilder->reset()->build(),
    ]
);

                }

                $languageMenu[] = [
                    'language' => $languageConf['title'],
                    'flag' => $languageConf['flag'] ?? 'default',
                    'link' => (string)$link,
                    'exists' => $translation !== null
                ];
            }

            $languageLinks[$member->getUid()] = $languageMenu;
        }

        $this->view->assignMultiple([
            'members' => $members,
            'languageLinks' => $languageLinks,
        ]);

        return $this->htmlResponse($this->view->render());
    }

    public function newAction(): ResponseInterface
    {
        $newMember = new Member();
        $newMember->setPid(self::STORAGE_PID);
        $this->view->assign('member', $newMember);
        return $this->htmlResponse();
    }

    public function showAction(Member $member): ResponseInterface
    {
        $this->view->assign('member', $member);
        return $this->htmlResponse();
    }

    public function createAction(Member $member): ResponseInterface
    {
        if ((int)$member->getPid() === 0) {
            $member->setPid(self::STORAGE_PID);
            $member->_setProperty('pid', self::STORAGE_PID);
        }

        $this->memberRepository->add($member);
        $this->persistenceManager->persistAll();
        $this->persistenceManager->clearState();

        $this->addFlashMessage('Member created.');
        return $this->redirect('index');
    }

    public function editAction(Member $member): ResponseInterface
    {
        $this->view->assign('member', $member);
        return $this->htmlResponse();
    }

    public function updateAction(Member $member): ResponseInterface
    {
        $this->memberRepository->update($member);
        $this->addFlashMessage('Member updated.');
        return $this->redirect('index');
    }

    public function deleteAction(Member $member): ResponseInterface
    {
        $this->memberRepository->remove($member);
        $this->addFlashMessage('Member deleted.');
        return $this->redirect('index');
    }

    public function createTranslationAction(int $l10nParent, int $sysLanguageUid): ResponseInterface
    {
        $logFile = GeneralUtility::getFileAbsFileName('typo3temp/translate_debug.log');
        file_put_contents($logFile, "[START] createTranslationAction at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

        try {
            file_put_contents($logFile, "[CHECK] l10nParent: $l10nParent | sysLanguageUid: $sysLanguageUid\n", FILE_APPEND);

            $original = $this->memberRepository->findByUid($l10nParent);
            if (!$original) {
                throw new \RuntimeException('Original member not found: ' . $l10nParent, 170000001);
            }
            file_put_contents($logFile, "[CHECK] Original member found: " . $original->getUid() . "\n", FILE_APPEND);

            $currentPageId = (int)($this->request->getArgument('id') ?? self::STORAGE_PID);
            file_put_contents($logFile, "[CHECK] Current PID: $currentPageId\n", FILE_APPEND);

            $newMember = new Member();
            $newMember->setPid($currentPageId);
            $newMember->_setProperty('pid', $currentPageId);
            $newMember->setL10nParent($l10nParent);
            $newMember->setSysLanguageUid($sysLanguageUid);
            file_put_contents($logFile, "[CHECK] New member initialized\n", FILE_APPEND);

            $this->copyTranslatableFields($original, $newMember, $sysLanguageUid);
            file_put_contents($logFile, "[CHECK] Translatable fields copied\n", FILE_APPEND);

            $this->memberRepository->add($newMember);
            $this->persistenceManager->persistAll();
            $this->persistenceManager->clearState();
            file_put_contents($logFile, "[CHECK] New member persisted: " . $newMember->getUid() . "\n", FILE_APPEND);

            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $editLink = $uriBuilder->buildUriFromRoute('record_edit', [
                'edit[tx_simple_domain_model_member][' . $newMember->getUid() . ']' => 'edit',
                'returnUrl' => (string)$this->uriBuilder->reset()->uriFor('index'),
            ]);
            file_put_contents($logFile, "[CHECK] Redirecting to edit: $editLink\n", FILE_APPEND);

            return $this->redirectToUri($editLink);
        } catch (\Throwable $e) {
            file_put_contents($logFile, "[ERROR] " . $e->getMessage() . "\n" . $e->getTraceAsString(), FILE_APPEND);
            echo '<pre style="color: red;">ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
            exit;
        }
    }

    private function getEnabledLanguages(): array
    {
        $languages = [];
        try {
            $site = $this->siteFinder->getSiteByPageId(self::STORAGE_PID);
            foreach ($site->getAllLanguages() as $language) {
                $languages[$language->getLanguageId()] = [
                    'title' => $language->getTitle(),
                    'iso' => $language->getLocale(),
                    'uid' => $language->getLanguageId(),
                    'flag' => $language->getFlagIdentifier(),
                ];
            }
        } catch (\Throwable) {
            $languages[0] = [
                'title' => 'Default',
                'iso' => 'en',
                'uid' => 0,
                'flag' => 'default',
            ];
        }
        return $languages;
    }

    private function copyTranslatableFields(Member $original, Member $target, int $languageId): void
    {
        $target->setPrefix($original->getPrefix());
        $target->setPrezime($original->getPrezime());
        $target->setFunkcija($original->getFunkcija());
        $target->setZvanje($original->getZvanje());
        $target->setOblast($original->getOblast());
        $target->setKonsultacije($original->getKonsultacije());
        $target->setEmail($original->getEmail());
        $target->setBiografija($original->getBiografija());
        $target->setRadovi($original->getRadovi());
        $target->setUdzbenici($original->getUdzbenici());
        $target->setCv($original->getCv());
        $target->setKarton($original->getKarton());
        $target->setImage($original->getImage());
        $target->setGroup($original->getGroup());
        $target->setSortiranje($original->getSortiranje());

        $siteLanguages = $this->siteFinder->getSiteByPageId(self::STORAGE_PID)->getLanguages();
        $langTitle = 'Unknown';
        foreach ($siteLanguages as $lang) {
            if ($lang->getLanguageId() === $languageId) {
                $langTitle = $lang->getTitle();
                break;
            }
        }
        $target->setName($original->getName() . ' (PREVOD: ' . $langTitle . ')');
    }

    private function logRequestParams(): void
    {
        $logFile = GeneralUtility::getFileAbsFileName('typo3temp/translate_debug.log');
        $log = "[REQUEST] " . date('Y-m-d H:i:s') . "\n";
        $log .= "GET:\n" . print_r($_GET, true) . "\n";
        $log .= "POST:\n" . print_r($_POST, true) . "\n";
        file_put_contents($logFile, $log, FILE_APPEND);
    }
}
