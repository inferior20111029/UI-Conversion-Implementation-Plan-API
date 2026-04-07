<?php

return [
    'name' => 'baseline',
    'cases' => [
        [
            'id' => 'skin-medium-dog',
            'description' => 'Dog with itchy ears and odor should map to skin / medium.',
            'pet' => [
                'name' => 'Bella',
                'type' => 'dog',
                'breed' => 'Shiba Inu',
                'weight' => 12.5,
                'birthday' => '2021-03-02',
                'health_records' => [
                    [
                        'type' => 'checkup',
                        'recorded_at' => '2026-03-20 09:30:00',
                        'value' => ['note' => 'Intermittent ear scratching reported'],
                    ],
                ],
            ],
            'message' => 'Bella 今天一直抓耳朵，耳朵有味道，而且狀況持續兩天。[signals:itchy_skin,ear_issue,ear_odor,persistent]',
            'expected' => [
                'category' => 'skin',
                'severity' => 'medium',
                'provider' => 'fallback',
            ],
        ],
        [
            'id' => 'digestive-medium-cat',
            'description' => 'Cat with vomiting and diarrhea should map to digestive / medium.',
            'pet' => [
                'name' => 'Milo',
                'type' => 'cat',
                'breed' => 'American Shorthair',
                'weight' => 4.8,
                'birthday' => '2022-07-10',
            ],
            'message' => 'Milo 昨晚吐了兩次，今天還有拉肚子，症狀還在持續。[signals:vomit,diarrhea,persistent]',
            'expected' => [
                'category' => 'digestive',
                'severity' => 'medium',
                'provider' => 'fallback',
            ],
        ],
        [
            'id' => 'respiratory-low-dog',
            'description' => 'Mild cough and sneezing should map to respiratory / low.',
            'pet' => [
                'name' => 'Lucky',
                'type' => 'dog',
                'breed' => 'Corgi',
                'weight' => 13.2,
                'birthday' => '2020-11-18',
            ],
            'message' => 'Lucky 這兩天偶爾咳嗽，也有打噴嚏，但精神還不錯。[signals:cough,sneeze]',
            'expected' => [
                'category' => 'respiratory',
                'severity' => 'low',
                'provider' => 'fallback',
            ],
        ],
        [
            'id' => 'urinary-high-cat',
            'description' => 'Cat with urinary difficulty and blood should map to urinary / high.',
            'pet' => [
                'name' => 'Nori',
                'type' => 'cat',
                'breed' => 'Mixed',
                'weight' => 5.1,
                'birthday' => '2018-01-04',
            ],
            'message' => 'Nori 一直跑貓砂盆但尿不太出來，還看到血，而且越來越頻繁。[signals:urine_issue,blood_urine,persistent,frequent]',
            'expected' => [
                'category' => 'urinary',
                'severity' => 'high',
                'provider' => 'fallback',
            ],
        ],
    ],
];
