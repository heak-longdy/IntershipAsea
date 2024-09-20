<?php

return [
    // [
    //     'path' => 'admin/dashboard',
    //     'active' => 'admin/dashboard',
    //     'permission' => 'dashboard-view',
    //     'name' => [
    //         'en' => 'Dashboard',
    //         'km' => 'ផ្ទាំងគ្រប់គ្រង',
    //     ],
    //     'icon' => 'bxs-dashboard',
    // ],
    // Booking
    [
        'path' => 'admin/booking/list/1',
        'active' => 'admin/booking*',
        'permission' => 'booking-view',
        'name' => [
            'en' => 'Bookings',
        ],
        'icon' => 'bx-bookmark',
    ],
    // user
    [
        'type'  => 'single',
        'active' => 'admin/customer/*',
        'path' => 'admin/customer/list/1',
        'permission' => ['user-view'],
        'name' => [
            'en' => 'Users',
        ],
        'icon' => 'bx-user',
    ],
    [
        'type'  => 'single',
        'active' => 'admin/partner/*',
        'path' => 'admin/partner/list/1',
        'permission' => ['partner-view'],
        'name' => [
            'en' => 'Partner',
        ],
        // 'icon' => 'bx-badge-check',
        'icon' => 'bxl-redux',
    ],
    [
        'type'  => 'single',
        'active' => 'admin/sector/*',
        'path' => 'admin/sector/list/1',
        'permission' => ['sector-view'],
        'name' => [
            'en' => 'Sector',
        ],
        // 'icon' => 'bx-badge-check',
        'icon' => 'bxl-redux',
    ],
    // admin
    [
        'type'  => 'single',
        'active' => 'admin/user/*',
        'path' => 'admin/user/list/1',
        'permission' => ['admin-view'],
        'name' => [
            'en' => 'Admin',
        ],
        'icon' => 'bx-briefcase',
    ],
    [
        'type'  => 'single',
        'active' => 'admin/job/*',
        'path' => 'admin/job/list/1',
        'permission' => ['job-view'],
        'name' => [
            'en' => 'Job',
        ],
        'icon' => 'bx-briefcase',
    ],
    [
        'type'  => 'single',
        'active' => 'admin/blog/*',
        'path' => 'admin/blog/list/1',
        'permission' => ['blog-view'],
        'name' => [
            'en' => 'blog',
        ],
        'icon' => 'bx-briefcase',
    ],
    [
        'type'  => 'single',
        'active' => 'admin/position/*',
        'path' => 'admin/position/list/1',
        'permission' => ['position-view'],
        'name' => [
            'en' => 'Position',
        ],
        'icon' => 'bxl-ok-ru',
    ],
    [
        'type'  => 'single',
        'active' => 'admin/placement-type/*',
        'path' => 'admin/placement-type/list/1',
        'permission' => ['placement-type-view'],
        'name' => [
            'en' => 'placement-type',
        ],
        'icon' => 'bxl-ok-ru',
    ],
    [
        'type'  => 'dropdown-single',
        'label' => 'Application',
        'active' => 'admin/contact/*,admin/about/privacy*',
        'permission' => ['contact-view', 'about-view'],
        'name' => [
            'en' => 'Setting',
        ],
        'icon' => 'bx-wrench',
        'children' => [
            [
                'path' => '#',
                'active' => '#',
                'permission' => 'contact-view',
                'name' => [
                    'en' => 'Contact',
                ],
                'icon' => 'bx-book',
            ],
            [
                'path' => '#',
                'active' => '#',
                'permission' => 'about-view',
                'name' => [
                    'en' => 'About',
                ],
                'icon' => 'bx-help-circle',
            ],
        ],
    ],
    // Setting
    [
        'type'  => 'dropdown-multiple',
        'label' => 'Setting & Application',
        'list-menu' => [
            [
                'active' => 'admin/currency/*,admin/page/privacy*',
                'permission' => ['currency-view', 'page-view'],
                'name' => [
                    'en' => 'Setting',
                ],
                'icon' => 'bx-shield-alt-2',
                'children' => [
                    [
                        'path' => 'admin/currency/list/1',
                        'active' => 'admin/currency/*',
                        'permission' => 'currency-view',
                        'name' => [
                            'en' => 'Currencies',
                        ],
                        'icon' => 'bx-shield-alt-2',
                    ],
                    [
                        'path' => 'admin/page/privacy',
                        'active' => 'admin/page/privacy',
                        'permission' => 'page-view',
                        'name' => [
                            'en' => 'Privacy',
                        ],
                        'icon' => 'bxs-check-shield',
                    ],
                ],
            ],
            [
                'active' => 'admin/report/revenue/*,admin/report/expense/*',
                'permission' => ['report-view', 'report-revenue', 'report-expense'],
                'name' => [
                    'en' => 'Reports',
                ],
                'icon' => 'bxs-report',
                'children' => [
                    [
                        'path' => 'admin/report/revenue/list/1',
                        'active' => 'admin/report/revenue/*',
                        'permission' => 'report-revenue',
                        'name' => [
                            'en' => 'Revenue',
                        ],
                        'icon' => 'bxl-xing',
                    ],
                    [
                        'path' => 'admin/report/expense/list/1',
                        'active' => 'admin/report/expense/*',
                        'permission' => 'report-expense',
                        'name' => [
                            'en' => 'Expense',
                        ],
                        'icon' => 'bxl-xing',
                    ],
                ],
            ],
        ]
    ],
    // endSetting
];
