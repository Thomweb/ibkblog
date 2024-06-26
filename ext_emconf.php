<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "ibkblog"
 *
 * Auto generated by Extension Builder 2020-04-30
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Blog Agentur IBK',
    'description' => 'Der Blog für die Webseite der Internetagentur Irma Berscheid-Kimeridze in Köln - Agentur IBK',
    'category' => 'plugin',
    'author' => 'Thomas Berscheid',
    'author_email' => 'thom@thomweb.de',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '12.4.64',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-8.9.99',
            'typo3' => '9.5.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'suggests' => [],
    'autoload' => [
        'psr-4' => ['Ibk\\Ibkblog\\' => 'Classes'],
        'classmap' => ['Classes'],
    ],
];
