<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Crop Variants Builder',
    'description' => 'Simplify writing cropVariants for TYPO3 Integrators/Developers',
    'version' => '1.0.3',
    'state' => 'stable',
    'author' => 'Josef Glatz',
    'author_email' => 'josefglatz@gmailcom',
    'clearCacheOnLoad' => true,
    'category' => 'be',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.13-12.0.0'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
           'JosefGlatz\\CropVariantsBuilder\\' => 'Classes'
        ]
     ],
];
