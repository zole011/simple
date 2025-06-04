<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tt_content.list_type_pi1',
        'simple_pi1',
        'EXT:simple/Resources/Public/Icons/user_plugin_simple.svg',
    ],
    'list_type',
    'simple'
);