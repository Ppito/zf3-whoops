<?php

namespace WhoopsErrorHandler;

return [
    'whoops' => [
        'editor'                  => 'phpstorm',
        'show_trace'              => [
            'ajax_display' => true,
            'cli_display'  => true,
        ],
        'template_render'         => 'zf3_whoops/simple_error',
        'visibility_service_name' => null,
    ],

    'service_manager' => [
        'factories' => [
            Service\WhoopsService::class  => Factory\Factory::class,
            Handler\PageHandler::class    => Factory\Factory::class,
            Handler\ConsoleHandler::class => Factory\Factory::class,
            Handler\AjaxHandler::class    => Factory\Factory::class,
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'zf3_whoops/simple_error' => __DIR__ . '/../view/render.phtml',
            'zf3_whoops/twig_error'   => __DIR__ . '/../view/twig/render.html.twig',
        ],
    ],
];
