<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Crop Variants Builder',
    'description' => 'Simplify writing cropVariants for TYPO3 Integrators/Developers',
    'version' => '1.0.1',
    'state' => 'stable',
    'author' => 'Josef Glatz',
    'author_email' => 'josefglatz@gmailcom',
    'clearCacheOnLoad' => true,
    'category' => 'be',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.13-10.4.99',
            'php' => '7.2.0-0.0.0'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
