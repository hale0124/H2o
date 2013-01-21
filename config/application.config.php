<?php
return array(
    'modules' => array(
        'ZfcAdmin',
        'Application',
        'AsseticBundle',
        'ZendDeveloperTools',
        'DoctrineModule',
        'DoctrineORMModule',
        'Article',
        'Page',
        'ZfcBase',
        'ZfcUser',
        'ZfcUserDoctrineORM',
        'BjyAuthorize',
        'Category',
        'DluTwBootstrap',
        'DOMPDFModule',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
