<?php

return [
    'whoops' => [
        'editor'     => 'phpstorm',
        'show_trace' => [
            'ajax_display' => true,
            'cli_display'  => true,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'WhoopsErrorHandler\Handler\Page' => WhoopsErrorHandler\Container\PageHandlerFactory::class,
            'WhoopsErrorHandler\Handler\Console' => WhoopsErrorHandler\Container\ConsoleHandlerFactory::class,
            'WhoopsErrorHandler\Handler\Ajax' => WhoopsErrorHandler\Container\AjaxHandlerFactory::class,
        ],
    ],
];
