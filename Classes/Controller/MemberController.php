<?php
declare(strict_types=1);

namespace Gmbit\Simple\Controller;

use Gmbit\Simple\Domain\Model\Member;
use Gmbit\Simple\Domain\Repository\MemberRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MemberController extends ActionController
{
    public function __construct(protected readonly MemberRepository $memberRepository)
    {
    }

    public function indexAction(): ResponseInterface
    {
        $this->view->assignMultiple([
            'members' => $this->memberRepository->findAllNotInline(),
        ]);

        return $this->htmlResponse();
    }

    public function createAction(Member $member): ResponseInterface
    {
        $member->setPid((int)($this->settings['singleFileUploadPid'] ?? 0));
        $this->memberRepository->add($member);

        return $this->redirect('index');
    }

    /**
     * @IgnoreValidation("member")
     */
    public function updateAction(Member $member): ResponseInterface
    {
        $this->view->assignMultiple([
            'member' => $member,
        ]);
        return $this->htmlResponse();
    }

    public function showAction(Member $member): ResponseInterface
    {
        $this->view->assignMultiple([
            'member' => $member,
        ]);

        return $this->htmlResponse();
    }

    public function newAction(): ResponseInterface
    {
        $this->view->assignMultiple([
            'member' => GeneralUtility::makeInstance(MemberRepository::class),
        ]);
        return $this->htmlResponse();
    }

	public function editAction(Member $member): ResponseInterface
    {
        $this->view->assignMultiple([
            'member' => $member,
        ]);

        return $this->htmlResponse();
    }
	
	public function deleteAction(Member $member): ResponseInterface
    {
        $this->memberRepository->remove($member);
        return $this->redirect('index');
    }
}