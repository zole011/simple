<?php

return [
    'web_simple' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/page/simple',
        'labels' => 'LLL:EXT:simple/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'Simple',
        'iconIdentifier' => 'extension-simple-module-icon',
        'controllerActions' => [
            \Gmbit\Simple\Controller\MemberController::class => [
                'index',
                'createTranslation',
                'show',
                'new',
                'create',
                'edit',
                'update',
                'delete',
            ],
        ],
        'view' => [
            'templateRootPaths' => [
                0 => 'EXT:simple/Resources/Private/Templates/Member/Backend/'
            ],
            'partialRootPaths' => [
                0 => 'EXT:simple/Resources/Private/Partials/'
            ],
            'layoutRootPaths' => [
                0 => 'EXT:simple/Resources/Private/Layouts/'
            ],
        ],
    ],
];