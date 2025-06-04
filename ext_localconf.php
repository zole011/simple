<?php
defined('TYPO3') or die();
use Gmbit\Simple\Controller\MemberFrontendController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Simple',
    'Pi1',
    [
        \Gmbit\Simple\Controller\MemberFrontendController::class => 'list, detail'
    ],
    [
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT 
    ]
);