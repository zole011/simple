<?php
defined('TYPO3') or die();

use Gmbit\Simple\Controller\MemberController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'Simple',
    'Pi1',
    [
        \Gmbit\Simple\Controller\MemberFrontendController::class => 'list, detail',
    ],
    [
        \Gmbit\Simple\Controller\MemberFrontendController::class => 'detail',
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT 
    ],
    
);
