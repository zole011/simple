<?php
defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Simple',
    'Pi1',
    [
        \Gmbit\Simple\Controller\MemberFrontendController::class => 'list, detail'
    ],
    [
        \Gmbit\Simple\Controller\MemberFrontendController::class => 'detail'
    ]
);