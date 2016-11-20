<?php

return [
    'whoops' => [
        'editor'     => 'phpstorm',
        'show_trace' => [
            'ajax_display' => true,
            'cli_display'  => true,
        ],
        'template_render' => 'zf3_whoops/simple_error',
    ],

    'service_manager' => [
        'factories' => [
            WhoopsErrorHandler\Service\WhoopsService::class  => WhoopsErrorHandler\Factory\Factory::class,
            WhoopsErrorHandler\Handler\PageHandler::class    => WhoopsErrorHandler\Factory\Factory::class,
            WhoopsErrorHandler\Handler\ConsoleHandler::class => WhoopsErrorHandler\Factory\Factory::class,
            WhoopsErrorHandler\Handler\AjaxHandler::class    => WhoopsErrorHandler\Factory\Factory::class,
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'zf3_whoops/simple_error' => __DIR__ . '/../view/render.phtml',
            'zf3_whoops/twig_error'   => __DIR__ . '/../view/twig/render.html.twig',
        ],
    ],
];
