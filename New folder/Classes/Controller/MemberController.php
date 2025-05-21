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
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Backend\Template\BackendTemplateView;

class MemberController extends ActionController
{
    public function __construct(
        protected readonly MemberRepository $memberRepository,
        protected readonly PersistenceManagerInterface $persistenceManager
    ) {}

    public function indexAction(): ResponseInterface
    {
        $members = $this->memberRepository->findAll();
        $this->view->assign('members', $members);
        return $this->htmlResponse();
    }

    public function newAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function createAction(): ResponseInterface
    {
        $data = $this->request->getArgument('newMember') ?? [];

        $member = new Member();
        $member->setName($data['name'] ?? '');
        $member->setPrefix($data['prefix'] ?? '');
        $member->setPrezime($data['prezime'] ?? '');
        $member->setFunkcija($data['funkcija'] ?? '');
        $member->setZvanje($data['zvanje'] ?? '');
        $member->setOblast($data['oblast'] ?? '');
        $member->setKonsultacije($data['konsultacije'] ?? '');
        $member->setEmail($data['email'] ?? '');
        $member->setBiografija($data['biografija'] ?? '');
        $member->setRadovi($data['radovi'] ?? '');
        $member->setUdzbenici($data['udzbenici'] ?? '');
        $member->setSortiranje($data['sortiranje'] ?? '');

        // 1. Prvo sačuvaj Member bez fajlova
        $this->memberRepository->add($member);
        $this->persistenceManager->persistAll(); // da dobije UID

        $uid = $member->getUid();

        // 2. Sada upload fajlova i veži ih za ovog Member-a
        //file_put_contents('E:/xampp83/htdocs/pep/upload_debug.txt', print_r($_FILES, true));

        $cvUpload = [
                'name'     => $_FILES['newMember']['name']['cv'] ?? null,
                'type'     => $_FILES['newMember']['type']['cv'] ?? null,
                'tmp_name' => $_FILES['newMember']['tmp_name']['cv'] ?? null,
                'error'    => $_FILES['newMember']['error']['cv'] ?? null,
                'size'     => $_FILES['newMember']['size']['cv'] ?? null,
        ];
        if (!empty($cvUpload['tmp_name'])) {
            $fileRef = $this->createFileReferenceForMember($cvUpload, 'cv', $uid);
            if ($fileRef) {
                $this->updateMemberFileReferenceField($uid, 'cv', $fileRef->getUid());
            }
        }
        $kartonUpload = [
                'name'     => $_FILES['newMember']['name']['karton'] ?? null,
                'type'     => $_FILES['newMember']['type']['karton'] ?? null,
                'tmp_name' => $_FILES['newMember']['tmp_name']['karton'] ?? null,
                'error'    => $_FILES['newMember']['error']['karton'] ?? null,
                'size'     => $_FILES['newMember']['size']['karton'] ?? null,
            ];
        if (!empty($kartonUpload['tmp_name'])) {
            $fileRef = $this->createFileReferenceForMember($kartonUpload, 'karton', $uid);
            if ($fileRef) {
                $this->updateMemberFileReferenceField($uid, 'karton', $fileRef->getUid());
            }
        }
        $imageUpload = [
                'name'     => $_FILES['newMember']['name']['image'] ?? null,
                'type'     => $_FILES['newMember']['type']['image'] ?? null,
                'tmp_name' => $_FILES['newMember']['tmp_name']['image'] ?? null,
                'error'    => $_FILES['newMember']['error']['image'] ?? null,
                'size'     => $_FILES['newMember']['size']['image'] ?? null,
            ];
        if (!empty($imageUpload['tmp_name'])) {
            $fileRef = $this->createFileReferenceForMember($imageUpload, 'image', $uid);
            if ($fileRef) {
                $this->updateMemberFileReferenceField($uid, 'image', $fileRef->getUid());
            }
        }

        $this->memberRepository->update($member);

        return $this->redirect('index');
    }

    public function editAction(Member $member): ResponseInterface
    {
        $this->view->assign('member', $member);
        return $this->htmlResponse();
    }

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
            $member->setCv($this->createFileReferenceFromUpload($cvUpload, 'cv'));
        }
        if (is_array($kartonUpload) && !empty($kartonUpload['tmp_name'])) {
            $member->setKarton($this->createFileReferenceFromUpload($kartonUpload, 'karton'));
        }
        if (is_array($imageUpload) && !empty($imageUpload['tmp_name'])) {
            $member->setImage($this->createFileReferenceFromUpload($imageUpload, 'image'));
        }

        $this->memberRepository->update($member);
        return $this->redirect('index');
    }

    public function showAction(Member $member): ResponseInterface
    {
        $this->view->assign('member', $member);
        return $this->htmlResponse();
    }

    public function deleteAction(Member $member): ResponseInterface
    {
        $this->memberRepository->remove($member);
        return $this->redirect('index');
    }

    protected function createFileReferenceFromUpload(array $uploadedFile, string $fieldName, int $pid = 0): ?FileReference
    {
        if (empty($uploadedFile['tmp_name'])) {
            return null;
        }
        $storageUid = 1; // default fileadmin storage
        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $storage = $storageRepository->findByUid($storageUid);

        // Odredi folder po tipu polja
        if ($fieldName === 'image') {
            $targetFolder = $storage->getFolder('user_upload/profimg');
        } else {
            $targetFolder = $storage->getFolder('user_upload/profdocs');
        }
        
        $file = $storage->addFile(
            $uploadedFile['tmp_name'],
            $targetFolder,
            $uploadedFile['name'],
            'changeName'
        );

        // Ručno kreiraj sys_file_reference
        $connection = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable('sys_file_reference');

        $connection->insert(
            'sys_file_reference',
            [
                'tstamp' => time(),
                'crdate' => time(),
                'uid_local' => $file->getUid(),
                'tablenames' => 'tx_simple_domain_model_member',
                'fieldname' => $fieldName,
                'pid' => $pid,
            ]
        );
        $fileReferenceUid = (int)$connection->lastInsertId();

        /** @var \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileReference */
        $fileReference = GeneralUtility::makeInstance(FileReference::class);
        $fileReference->_setProperty('uid', $fileReferenceUid);
        $fileReference->setOriginalResource($file);

        return $fileReference;
    }

    protected function createFileReferenceForMember(array $uploadedFile, string $fieldName, int $uidForeign, int $pid = 0): ?FileReference
    {
        if (empty($uploadedFile['tmp_name'])) {
            return null;
        }
        $storageUid = 1;
        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $storage = $storageRepository->findByUid($storageUid);

        // Odredi folder po tipu polja
        if ($fieldName === 'image') {
            $targetFolder = $storage->getFolder('user_upload/profimg');
        } else {
            $targetFolder = $storage->getFolder('user_upload/profdocs');
        }

        $file = $storage->addFile(
            $uploadedFile['tmp_name'],
            $targetFolder,
            $uploadedFile['name'],
            'changeName'
        );


        // Ručno kreiraj sys_file_reference sa UID Member-a
        $connection = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable('sys_file_reference');

        $connection->insert(
            'sys_file_reference',
            [
                'tstamp' => time(),
                'crdate' => time(),
                'uid_local' => $file->getUid(),
                'tablenames' => 'tx_simple_domain_model_member',
                'uid_foreign' => $uidForeign,
                'fieldname' => $fieldName,
                'pid' => $pid,
            ]
        );
        $fileReferenceUid = (int)$connection->lastInsertId();

        // Pravi FAL FileReference objekat preko ResourceFactory
        $resourceFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
        $falFileReference = $resourceFactory->getFileReferenceObject($fileReferenceUid);

        // Pravi Extbase FileReference objekat
        $extbaseFileReference = GeneralUtility::makeInstance(FileReference::class);
        $extbaseFileReference->_setProperty('uid', $fileReferenceUid);
        $extbaseFileReference->setOriginalResource($falFileReference);

        return $extbaseFileReference;
    }

    protected function updateMemberFileReferenceField(int $memberUid, string $fieldName, int $fileReferenceUid): void
    {
        $connection = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable('tx_simple_domain_model_member');
        $connection->update(
            'tx_simple_domain_model_member',
            [$fieldName => $fileReferenceUid],
            ['uid' => $memberUid]
        );
    }
}