<?php
defined('TYPO3') or die();

(function () {



    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Ibkblog',
            'Blog',
            'Blog Standard'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Ibkblog',
            'Kategories',
            'Blog Kategories'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Ibkblog',
            'Tag',
            'Blog Tags'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Ibkblog',
            'Taglist',
            'Blog Taglist'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Ibkblog',
            'Pagelist',
            'Blog Pagelist'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Ibkblog',
            'Pagegrid',
            'Blog Pagegrid'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Ibkblog',
            'Pageall',
            'Blog Pageall'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('ibkblog', 'Configuration/TypoScript', 'Blog');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_ibkblog_domain_model_blog', 'EXT:ibkblog/Resources/Private/Language/locallang_csh_tx_ibkblog_domain_model_blog.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ibkblog_domain_model_blog');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_ibkblog_domain_model_kategorie', 'EXT:ibkblog/Resources/Private/Language/locallang_csh_tx_ibkblog_domain_model_kategorie.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ibkblog_domain_model_kategorie');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_ibkblog_domain_model_tag', 'EXT:ibkblog/Resources/Private/Language/locallang_csh_tx_ibkblog_domain_model_tag.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ibkblog_domain_model_tag');

})();

