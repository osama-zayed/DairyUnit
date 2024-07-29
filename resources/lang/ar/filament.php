<?php
return [
    'resources' => [
        'role' => [
            'fields' => [
                'name' => 'الصلاحية',
            ],
            'options' => [
                'institution' => 'المؤاسسة',
                'association' => 'الجمعية',
                'representative' => 'المندوب',
                'collector' => 'المجمع',
            ],
        ],
        'receiptFromAssociation'=>[
            "clean"=>'نظيفة',
            "somewhat_clean"=>'مقبولة',
            "not_clean"=>'سيئة',
            "on"=>'يعمل',
            "off"=>'لا يعمل',
            "not_available"=>'لا يوجد',
        ]
    ],
];