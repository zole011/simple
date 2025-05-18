<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.pluginTitle',
        'simple_pi1'
    ],
    'CType',
    'simple'
);