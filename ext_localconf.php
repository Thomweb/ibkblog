<?php

declare(strict_types=1);

use Ibk\Ibkblog\Controller\BlogController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'Blog',
    [
        BlogController::class => 'list, more, new, create, pagelist, pagegrid, pageall, searchList, show, showByLink, page, soap'
    ],
    // non-cacheable actions
    [
        //BlogController::class => 'list, more, new, create, pagelist, pagegrid, pageall, searchList, showByLink, show, page, soap'
        BlogController::class => 'list, more, new, create, searchList, showByLink, show, page, soap'
    ]
);

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'PluginOne',
    [
        BlogController::class => 'more'
    ],
    // non-cacheable actions
    [
        BlogController::class => 'more'
    ]
);

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'Kategories',
    [
        BlogController::class => 'katView, katList'
    ],
    // non-cacheable actions
    [
        BlogController::class => 'katView, katList'
    ]
);

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'Tag',
    [
        BlogController::class => 'tagShow'
    ],
    // non-cacheable actions
    [
        BlogController::class => 'tagShow'
    ]
);

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'Taglist',
    [
        BlogController::class => 'tagBlogList, tagBlogShow'
    ],
    // non-cacheable actions
    [
        BlogController::class => 'tagBlogList, tagBlogShow'
    ]
);

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'Pagelist',
    [
        BlogController::class => 'pagelist'
    ],
    // non-cacheable actions
    [
        BlogBlogController::class => 'pagelist'
    ]
);

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'Pageall',
    [
        BlogController::class => 'pageall'
    ],
    // non-cacheable actions
    [
        BlogBlogController::class => 'pageall'
    ]
);

ExtensionUtility::configurePlugin(
    'Ibkblog',
    'Pagegrid',
    [
        BlogController::class => 'pagegrid'
    ],
    // non-cacheable actions
    [
        BlogController::class => 'pagegrid'
    ]
);
