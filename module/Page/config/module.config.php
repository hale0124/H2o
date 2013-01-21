<?php
namespace Page;
return array(
    'controllers' => array(
        'invokables' => array(
            'Page\Controller\Page' => 'Page\Controller\PageController',
            'Page\Controller\Administration' => 'Page\Controller\AdministrationController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'pages-administration' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/pages-administration[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Page\Controller\Administration',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'page' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/page[/:name]',
                    'constraints' => array(
                        'name' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Page\Controller\Page',
                        'action'     => 'index',
                        'name'       => 'index'
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Page' => __DIR__ . '/../view',
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
