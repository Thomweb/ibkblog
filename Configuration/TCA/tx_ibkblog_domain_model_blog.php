<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog',
        'label' => 'titel',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,email,titel,kurzfassung,inhalt,link',
        'iconfile' => 'EXT:ibkblog/Resources/Public/Icons/tx_ibkblog_domain_model_blog.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, email, titel, kurzfassung, inhalt, datum, link, kategorie, tags',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, email, titel, kurzfassung, inhalt, datum, link, kategorie, tags, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_ibkblog_domain_model_blog',
                'foreign_table_where' => 'AND {#tx_ibkblog_domain_model_blog}.{#pid}=###CURRENT_PID### AND {#tx_ibkblog_domain_model_blog}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'titel' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.titel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kurzfassung' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.kurzfassung',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'inhalt' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.inhalt',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
				'enableRichtext' => true,
                'eval' => 'trim'
            ]
        ],
        'datum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.datum',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 12,
                'eval' => 'datetime',
                'default' => null,
            ],
        ],
        'link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.link',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kategorie' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.kategorie',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_ibkblog_domain_model_kategorie',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'tags' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ibkblog/Resources/Private/Language/locallang_db.xlf:tx_ibkblog_domain_model_blog.tags',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingleBox',
                'foreign_table' => 'tx_ibkblog_domain_model_tag',
                'MM' => 'tx_ibkblog_blog_tag_mm',
            ],
            
        ],
    
    ],
];
