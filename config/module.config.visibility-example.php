<?php

namespace WhoopsErrorHandler;

use Application\Service\VisibilityService;

return [
    'whoops' => [
        'editor'                  => 'phpstorm',
        'show_trace'              => [
            'ajax_display' => true,
            'cli_display'  => true,
        ],
        'template_render'         => 'zf3_whoops/simple_error',
        // Specify the class name
        'visibility_service_name' => VisibilityService::class,
    ],

    'service_manager' => [
        'factories' => [
            Service\WhoopsService::class  => Factory\Factory::class,
            Handler\PageHandler::class    => Factory\Factory::class,
            Handler\ConsoleHandler::class => Factory\Factory::class,
            Handler\AjaxHandler::class    => Factory\Factory::class,
            // register visibility class
            VisibilityService::class  => Factory\Factory::class,
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'zf3_whoops/simple_error' => __DIR__ . '/../view/render.phtml',
            'zf3_whoops/twig_error'   => __DIR__ . '/../view/twig/render.html.twig',
        ],
    ],
];
