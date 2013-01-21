<?php
return array(
    'bjyauthorize' => array(

        'default_role' => 'guest',
        'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserDoctrine',

        'role_providers' => array(
            'BjyAuthorize\Provider\Role\Doctrine' => array(
                'table'             => 'user_role',
                'role_id_field'     => 'role_id',
                'parent_role_field' => 'parent',
            ),
        ),

        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'home', 'roles' => array('guest', 'user')),
                
                array('route' => 'zfcuser', 'roles' => array('user')),
                array('route' => 'zfcuser/logout', 'roles' => array('user')),
                array('route' => 'zfcuser/login', 'roles' => array('guest','user')),
                array('route' => 'zfcuser/register', 'roles' => array('guest')),
                array('route' => 'zfcuser/authenticate', 'roles' => array('guest')),
                array('route' => 'zfcuser/changepassword', 'roles' => array('user')),
                array('route' => 'zfcuser/changeemail', 'roles' => array('user')),
                
                array('route' => 'articles', 'roles' => array('guest','user')),
                array('route' => 'article', 'roles' => array('guest','user')),
                array('route' => 'pdf', 'roles' => array('guest','user')),
                array('route' => 'page', 'roles' => array('guest','user')),
                
                array('route' => 'zfcadmin/articles-administration', 'roles' => array('user')),
                array('route' => 'zfcadmin/pages-administration', 'roles' => array('user')),
                array('route' => 'zfcadmin/categories-administration', 'roles' => array('user')),
                
                array('route' => 'doctrine_orm_module_yuml', 'roles' => array('guest','user')),
            ),
        ),
    ),
);