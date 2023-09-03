<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Crop Variants Builder',
    'description' => 'Simplify writing cropVariants for TYPO3 Integrators/Developers',
    'version' => '1.0.3',
    'state' => 'stable',
    'author' => 'Josef Glatz',
    'author_email' => 'typo3@josefglatz.at',
    'clearCacheOnLoad' => true,
    'category' => 'be',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99'
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
