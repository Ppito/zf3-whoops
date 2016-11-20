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
        'factories' => [
            WhoopsErrorHandler\Handler\PageHandler::class    => WhoopsErrorHandler\Factory\HandlerFactory::class,
            WhoopsErrorHandler\Handler\ConsoleHandler::class => WhoopsErrorHandler\Factory\HandlerFactory::class,
            WhoopsErrorHandler\Handler\AjaxHandler::class    => WhoopsErrorHandler\Factory\HandlerFactory::class,
        ],
    ],
];
