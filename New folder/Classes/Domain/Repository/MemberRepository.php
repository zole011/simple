<?php
declare(strict_types=1);

namespace Gmbit\Simple\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;


class MemberRepository extends Repository
{
    public function initializeObject(): void
    {
        $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    public function findAllNotInline()
    {
     $query = $this->createQuery();
    return $query->execute();
    }
    public function findByGroup(string $group)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('group', $group)
        );
        return $query->execute();
    }
}