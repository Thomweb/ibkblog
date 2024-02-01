<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ibk.' . $_EXTKEY,
	'Blog',
	array(
		'Blog' => 'list, new, create, searchList, show, page, pagelist, pagegrid',
		
	),
	// non-cacheable actions
	array(
		'Blog' => 'list, new, create, searchList, show, page, pagelist, pagegrid',
		
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ibk.' . $_EXTKEY,
	'Searchform',
	array(
		'Blog' => 'searchForm',
	),
	// non-cacheable actions
	array(
		'Blog' => 'searchForm',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ibk.' . $_EXTKEY,
	'Tag',
	array(
		'Blog' => 'tagShow, tagAdd, tagUpdate, tagDelete',
	),
	// non-cacheable actions
	array(
		'Blog' => 'tagShow, tagShow, tagUpdate, tagDelete',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ibk.' . $_EXTKEY,
	'Taglist',
	array(
		'Blog' => 'tagBlogList, tagBlogShow',
	),
	// non-cacheable actions
	array(
		'Blog' => 'tagBlogList, tagBlogShow',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ibk.' . $_EXTKEY,
	'Kategories',
	array(
		'Blog' => 'katView, katList, show',
	),
	// non-cacheable actions
	array(
		'Blog' => 'katView, katList, show',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ibk.' . $_EXTKEY,
	'Pagelist',
	array(
		'Blog' => 'page',
	),
	// non-cacheable actions
	array(
		'Blog' => 'page',
	)
); 




