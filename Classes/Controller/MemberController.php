<?php
declare(strict_types=1);

namespace Gmbit\Simple\Controller;

use Gmbit\Simple\Domain\Model\Member;
use Gmbit\Simple\Domain\Repository\MemberRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MemberController extends ActionController
{
    protected MemberRepository $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    protected function createFileReferenceFromUpload(array $uploadedFile): ?FileReference
    {
        if (empty($uploadedFile['tmp_name'])) {
            return null;
        }
        $storageUid = 1; // default fileadmin storage
        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $storage = $storageRepository->findByUid($storageUid);

        $file = $storage->addFile(
            $uploadedFile['tmp_name'],
            $storage->getRootLevelFolder(),
            $uploadedFile['name'],
            'changeName'
        );

        /** @var FileReference $fileReference */
        $fileReference = GeneralUtility::makeInstance(FileReference::class);
        $fileReference->setOriginalResource($file);

        return $fileReference;
    }

    protected function createSysFileReference(int $fileUid, string $fieldName): int
    {
        $connection = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable('sys_file_reference');

        $connection->insert(
            'sys_file_reference',
            [
                'tstamp' => time(),
                'crdate' => time(),
                'uid_local' => $fileUid,
                'tablenames' => 'tx_simple_domain_model_member',
                'fieldname' => $fieldName, // biće popunjeno automatski
                'pid' => 0,
            ]
        );
        return (int)$connection->lastInsertId();
    }

    public function indexAction(): ResponseInterface
    {
        $members = $this->memberRepository->findAll();
        $this->view->assign('members', $members);
        return $this->htmlResponse();
    }

    public function showAction(Member $member): ResponseInterface
    {
        $this->view->assign('member', $member);
        return $this->htmlResponse();
    }

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("newMember")
     */
    public function newAction(): ResponseInterface
    {
        $newMember = new Member();
        $this->view->assign('newMember', $newMember);
        return $this->htmlResponse();
    }

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("newMember")
     */
    public function createAction(): ResponseInterface
    {
        $data = $this->request->getArgument('newMember') ?? [];
        $newMember = new Member();
        $newMember->setName($data['name'] ?? '');
        $newMember->setPrefix($data['prefix'] ?? '');
        $newMember->setPrezime($data['prezime'] ?? '');
        $newMember->setFunkcija($data['funkcija'] ?? '');
        $newMember->setZvanje($data['zvanje'] ?? '');
        $newMember->setOblast($data['oblast'] ?? '');
        $newMember->setKonsultacije($data['konsultacije'] ?? '');
        $newMember->setEmail($data['email'] ?? '');
        $newMember->setBiografija($data['biografija'] ?? '');
        $newMember->setRadovi($data['radovi'] ?? '');
        $newMember->setUdzbenici($data['udzbenici'] ?? '');
        $newMember->setSortiranje($data['sortiranje'] ?? '');

        $cvUpload = $_FILES['newMember']['cv'] ?? null;
        $kartonUpload = $_FILES['newMember']['karton'] ?? null;
        $imageUpload = $_FILES['newMember']['image'] ?? null;

        if (is_array($cvUpload) && !empty($cvUpload['tmp_name'])) {
            $newMember->setCv($this->createFileReferenceFromUpload($cvUpload));
        }
        if (is_array($kartonUpload) && !empty($kartonUpload['tmp_name'])) {
            $newMember->setKarton($this->createFileReferenceFromUpload($kartonUpload));
        }
        if (is_array($imageUpload) && !empty($imageUpload['tmp_name'])) {
            $newMember->setImage($this->createFileReferenceFromUpload($imageUpload));
        }

        $this->memberRepository->add($newMember);
        return $this->redirect('index');
    }

    public function editAction(Member $member): ResponseInterface
    {
        $this->view->assign('member', $member);
        return $this->htmlResponse();
    }

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("member")
     */
    public function updateAction(Member $member): ResponseInterface
    {
        $data = $this->request->getArgument('newMember') ?? [];
        $member->setName($data['name'] ?? $member->getName());
        $member->setPrefix($data['prefix'] ?? $member->getPrefix());
        $member->setPrezime($data['prezime'] ?? $member->getPrezime());
        $member->setFunkcija($data['funkcija'] ?? $member->getFunkcija());
        $member->setZvanje($data['zvanje'] ?? $member->getZvanje());
        $member->setOblast($data['oblast'] ?? $member->getOblast());
        $member->setKonsultacije($data['konsultacije'] ?? $member->getKonsultacije());
        $member->setEmail($data['email'] ?? $member->getEmail());
        $member->setBiografija($data['biografija'] ?? $member->getBiografija());
        $member->setRadovi($data['radovi'] ?? $member->getRadovi());
        $member->setUdzbenici($data['udzbenici'] ?? $member->getUdzbenici());
        $member->setSortiranje($data['sortiranje'] ?? $member->getSortiranje());

        $cvUpload = $_FILES['newMember']['cv'] ?? null;
        $kartonUpload = $_FILES['newMember']['karton'] ?? null;
        $imageUpload = $_FILES['newMember']['image'] ?? null;

        if (is_array($cvUpload) && !empty($cvUpload['tmp_name'])) {
            $member->setCv($this->createFileReferenceFromUpload($cvUpload));
        }
        if (is_array($kartonUpload) && !empty($kartonUpload['tmp_name'])) {
            $member->setKarton($this->createFileReferenceFromUpload($kartonUpload));
        }
        if (is_array($imageUpload) && !empty($imageUpload['tmp_name'])) {
            $member->setImage($this->createFileReferenceFromUpload($imageUpload));
        }

        $this->memberRepository->update($member);
        return $this->redirect('index');
    }

    public function deleteAction(Member $member): ResponseInterface
    {
        $this->memberRepository->remove($member);
        return $this->redirect('index');
    }

    // initializeCreateAction i initializeUpdateAction nisu potrebni u ovom pristupu!
}
