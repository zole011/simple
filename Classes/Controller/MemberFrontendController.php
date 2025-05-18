<?php

declare(strict_types=1);

namespace Gmbit\Simple\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Gmbit\Simple\Domain\Repository\MemberRepository;
use Gmbit\Simple\Domain\Model\Member;

final class MemberFrontendController extends ActionController
{
    protected MemberRepository $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

public function listAction(): ResponseInterface
{
    // 1️⃣ Definiši statičke grupe
    $groups = [
        'nastavnici' => 'Nastavnici',
        'direktori'  => 'Direktori',
    ];

    // 2️⃣ Po defaultu uzmi 'nastavnici', osim ako je u URL-u poslat drugi argument
    $currentGroup = $this->request->hasArgument('group')
        ? $this->request->getArgument('group')
        : 'nastavnici';

    // 3️⃣ Filtriraj članove po izabranoj grupi
    $members = $this->memberRepository->findByGroup($currentGroup);

    // 4️⃣ Prosledi promenljive u view
    $this->view->assignMultiple([
        'members'      => $members,
        'groups'       => $groups,
        'currentGroup' => $currentGroup,
    ]);

    return $this->htmlResponse();
}

public function detailAction(Member $member): ResponseInterface
{
    // 1️⃣ Definiši statičke grupe (isto kao u listAction)
    $groups = [
        'nastavnici' => 'Nastavnici',
        'direktori'  => 'Direktori',
    ];

    // 2️⃣ Pročitaj iz URL-a eventualni filter (nije obavezno za detail, može ostati null)
    $currentGroup = $this->request->hasArgument('group')
        ? $this->request->getArgument('group')
        : null;

    // 3️⃣ Prosledi sve promenljive u view
    $this->view->assignMultiple([
        'member'       => $member,
        'groups'       => $groups,
        'currentGroup' => $currentGroup,
    ]);

    // 4️⃣ Renderuj standardno detail šablon
    return $this->htmlResponse();
}


    // Dodaj druge akcije po potrebi (editAction, updateAction itd.)
}
