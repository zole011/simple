<?php
return [
    'Pi1' => [
        'controllerActions' => [
            \Gmbit\Simple\Controller\MemberFrontendController::class => [
                'list', 'detail'
            ],
        ],
        'nonCacheableActions' => [
            \Gmbit\Simple\Controller\MemberFrontendController::class => [
                'detail'
            ],
        ],
    ],
];
