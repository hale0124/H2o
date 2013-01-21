<?php

$settings = array(
    'use_admin_layout' => true,
    'admin_layout_template' => 'layout/myadmin',
);

return array(
    'zfcadmin' => $settings,
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'zfcadmin', 'roles' => array('user','admin')),
            ),
        ),
    ),
);
