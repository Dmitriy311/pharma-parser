<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    'web_page_parser' => [[], ['_controller' => 'App\\Controller\\WebPageParserController::parse'], [], [['text', '/parse']], [], [], []],
    'app_webpageparser_loadfile' => [['file'], ['_controller' => 'App\\Controller\\WebPageParserController::loadfile'], [], [['variable', '/', '[^/]++', 'file', true], ['text', '/app/storage']], [], [], []],
    'App\Controller\WebPageParserController::parse' => [[], ['_controller' => 'App\\Controller\\WebPageParserController::parse'], [], [['text', '/parse']], [], [], []],
    'App\Controller\WebPageParserController::loadfile' => [['file'], ['_controller' => 'App\\Controller\\WebPageParserController::loadfile'], [], [['variable', '/', '[^/]++', 'file', true], ['text', '/app/storage']], [], [], []],
];