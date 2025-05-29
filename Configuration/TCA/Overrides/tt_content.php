<?php
defined('TYPO3') or die();
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'Simple Plugin',
        'simple_pi1',
        'EXT:simple/Resources/Public/Icons/ext_icon.svg.svg',
    ],
    'list_type',
    'simple'
);
        
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'simple',
    'Configuration/TypoScript',
    'Simple Configuration'
);