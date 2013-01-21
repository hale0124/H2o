<?php
/*
 * http://samsonasik.wordpress.com/2012/11/18/zend-framework-2-dynamic-navigation-using-zend-navigation/#more-1857
 * http://zf2.readthedocs.org/en/latest/modules/zend.view.helpers.navigation.html
 */
return array(
    'navigation' => array(
        'admin' => array(
            'articles-admin' => array(
                'label' => 'Articles',
                'route' => 'zfcadmin/articles-administration',
            ),
            'pages-admin' => array(
                'label' => 'Pages',
                'route' => 'zfcadmin/pages-administration',
            ),
            'categories-admin' => array(
                'label' => 'Categories',
                'route' => 'zfcadmin/categories-administration',
            ),
        ),
    ),
);