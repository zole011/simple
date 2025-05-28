<?php
defined('TYPO3') or die();

use Gmbit\Simple\Controller\MemberFrontendController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'Simple',
    'Pi1',
    [
        \Gmbit\Simple\Controller\MemberFrontendController::class => 'list, detail',
    ],
    [
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT 
    ],
    
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = 1;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '*';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['exceptionalErrors'] = E_ALL;