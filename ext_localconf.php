<?php
defined('TYPO3') or die();

use Gmbit\Simple\Controller\MemberController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'simple',
    'Pi1',
    [
        MemberController::class => 'list,new,create,show,edit,update',
    ],
    // non-cacheable actions
    [
        MemberController::class => 'list,new,create,show,edit,update',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);