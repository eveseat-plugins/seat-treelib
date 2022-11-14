<?php
return [
    'profile' => [
        'name'          => 'user',
        'label'         => 'treelib::treelib.user_sidebar_menu',
        'icon'          => 'fas fa-user',
        'route_segment' => 'profile',
        'entries'       => [
            //hack user profile link into seat
            [
                'name'  => 'profile',
                'label' => 'web::seat.profile',
                'icon'  => 'fas fa-user',
                'route' => 'profile.view',
            ],
            //treelib settings page
            [
                'name'  => 'treelib_settings',
                'label' => 'treelib::treelib.treelib_settings',
                'icon'  => 'fas fa-cogs',
                'route' => 'treelib.settings',
            ]
        ]
    ]
];