<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Crop Variants Builder',
    'description' => 'Simplify writing cropVariants for TYPO3 Integrators/Developers',
    'version' => '2.0.1',
    'state' => 'stable',
    'author' => 'Josef Glatz',
    'author_email' => 'typo3@josefglatz.at',
    'clearCacheOnLoad' => true,
    'category' => 'be',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-13.4.99'
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
