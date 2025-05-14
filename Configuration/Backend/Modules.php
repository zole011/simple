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
        'iconIdentifier' => 'simple-module',
        'controllerActions' => [
            \Gmbit\Simple\Controller\MemberController::class => [
                'index',
                'show',
                'new',
                'create',
                'edit',
                'update',
                'delete',
            ],
        ],
    ],
];
