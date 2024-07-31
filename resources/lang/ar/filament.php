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
            "somewhat_clean"=>'متوسط النظافة',
            "not_clean"=>'غير نظيفة',
            "on"=>'يعمل',
            "off"=>'لا يعمل',
            "not_available"=>'لا يوجد',
        ]
    ],
];