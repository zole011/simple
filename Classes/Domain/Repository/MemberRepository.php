<?php
declare(strict_types=1);

namespace Gmbit\Simple\Domain\Repository;

use Gmbit\Simple\Domain\Model\Member;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;


class MemberRepository extends Repository
{
    /**
     * Finds a translation for a given parent UID and language UID.
     *
     * @param int $l10nParentUid The UID of the original (parent) record.
     * @param int $sysLanguageUid The UID of the target language.
     * @return \Gmbit\Simple\Domain\Model\Member|null The translation record, or null if not found.
     */
    public function findByLanguageAndParent(int $languageId, int $parentId): ?Member
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('sysLanguageUid', $languageId),
                $query->equals('l10nParent', $parentId)
            )
        );
        return $query->execute()->getFirst();
    }
    public function initializeObject(): void
    {
        $context = GeneralUtility::makeInstance(Context::class);
        $languageAspect = $context->getAspect('language');

        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setLanguageAspect($languageAspect);
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);
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
    public function findTranslationForOriginal(int $originalUid, int $languageUid): ?Member
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('l10n_parent', $originalUid),
                $query->equals('sys_language_uid', $languageUid)
            )
        );
        $query->setLimit(1); // Potrebno je samo jedan rezultat
        // Vrati prvi rezultat ili null ako ništa nije pronađeno
        return $query->execute()->getFirst();
    }
    /**
     * @return Member[] indexed by l10n_parent and sys_language_uid
     */
    public function findAllTranslationsIndexed(): array
    {
        $query = $this->createQuery();
        $query->matching(
            $query->greaterThan('sysLanguageUid', 0)
        );

        $translations = [];
        foreach ($query->execute() as $translation) {
            $parentId = $translation->getL10nParent();
            $langId = $translation->getSysLanguageUid();
            $translations[$parentId][$langId] = $translation;
        }
        return $translations;
    }
}