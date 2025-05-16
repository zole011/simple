<?php
defined('TYPO3') or die();

use Gmbit\Simple\Controller\MemberController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'simple',
    'Pi1', // Frontend plugin
    [
        MemberController::class => 'index,show',
    ],
    [
        MemberController::class => 'index,show',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'simple',
    'Backend', // Backend modul
    [
        MemberController::class => 'index,show,new,create,edit,update,delete',
    ],
    [
        MemberController::class => 'new,create,edit,update,delete',
    ]
);