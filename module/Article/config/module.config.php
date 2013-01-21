<?php

namespace Article;

return array(
    'controllers' => array(
        'invokables' => array(
            'Article\Controller\Article' => 'Article\Controller\ArticleController',
            'Article\Controller\Administration' => 'Article\Controller\AdministrationController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'articles-administration' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/articles-administration[/:action[/tab/:tab[/id/:id]]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'tab' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Article\Controller\Administration',
                                'action' => 'index',
                                'tab' => 'articles'
                            ),
                        ),
                    ),
                ),
            ),
            'articles' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/articles[/group/:group[/page/:page]]',
                    'constraints' => array(
                        'page' => '[0-9]+',
                        'group' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Article\Controller\Article',
                        'action' => 'index',
                        'page' => 1,
                        'group' => 1
                    ),
                ),
            ),
            'article' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/article[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Article\Controller\Article',
                        'action' => 'get',
                        'id' => 1,
                    ),
                ),
            ),
            'pdf' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/pdf[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Article\Controller\Article',
                        'action' => 'pdf',
                        'id' => null,
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Article' => __DIR__ . '/../view',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
                'text_domain' => __NAMESPACE__,
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    )
);
