<?php

declare(strict_types=1);

namespace Gmbit\Simple\Controller;

use Gmbit\Simple\Domain\Model\Member;
use Gmbit\Simple\Domain\Repository\MemberRepository;
use Psr\Http\Message\ResponseInterface;
//use Psr\Log\LoggerInterface;
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
        //protected readonly LoggerInterface $logger,
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

                $translationUid = $translations[$member->getUid()][$languageId] ?? null;

                if ($translationUid !== null) {
                    $link = $uriBuilder->buildUriFromRoute('record_edit', [
                        'edit[tx_simple_domain_model_member][' . $translationUid . ']' => 'edit',
                        'returnUrl' => (string)$this->uriBuilder->reset()->uriFor('index')
                    ]);
                }  else {
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
                    'exists' => $translationUid !== null
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
}
