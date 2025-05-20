<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        // Dodajemo polja za višejezičnost
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => 'EXT:simple/Resources/Public/Icons/Extension.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    hidden, sys_language_uid, l10n_parent, l10n_diffsource,
                    name, prefix, prezime, funkcija, zvanje, oblast, konsultacije, email, sortiranje,
                --div--;LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tabs.content,
                    biografija, radovi, udzbenici,
                --div--;LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tabs.media,
                    cv, karton, image
            ',
        ],
    ],
    'columns' => [
        // Polja za višejezičnost
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
                'default' => 0, // 0 je podrazumevani jezik (srpski)
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_simple_domain_model_member',
                'foreign_table_where' => 'AND {#tx_simple_domain_model_member}.{#pid}=###CURRENT_PID### AND {#tx_simple_domain_model_member}.{#sys_language_uid} IN (-1,0)',
                'default' => 0
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'l10n_state' => [
            'exclude' => true,
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_state',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_state.custom', ''],
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_state.ignoreMissingSourceField', 'ignoreMissingSourceField'],
                ],
                'default' => '',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
            ]
        ],
        
        // Postojeća polja sa dodatom podrškom za višejezičnost
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
            ],
        ],
        'prefix' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.prefix',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
            ],
        ],
        'prezime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.prezime',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
            ],
        ],
        'funkcija' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.funkcija',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'zvanje' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.zvanje',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'oblast' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.oblast',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'konsultacije' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.konsultacije',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.email',
            'config' => [
                'type' => 'input',
                'eval' => 'email',
                'size' => 30,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
            ],
        ],
        'biografija' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.biografija',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
                'cols' => 40,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'radovi' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.radovi',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
                'cols' => 40,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'udzbenici' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.udzbenici',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
                'cols' => 40,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'cv' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.cv',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'Add File',
                ],
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
                'foreign_match_fields' => [
                    'fieldname' => 'cv',
                ],
                'allowed' => 'pdf',
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'karton' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.karton',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'Add File',
                ],
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
                'foreign_match_fields' => [
                    'fieldname' => 'karton',
                ],
                'allowed' => 'pdf',
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.image',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'Add File',
                ],
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
                'foreign_match_fields' => [
                    'fieldname' => 'image',
                ],
                'allowed' => 'jpg,png,gif',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ],
            ],
        ],
        'sortiranje' => [
            'exclude' => true,
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.sortiranje',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Direktor', 'Direktor'],
                    ['Direktor za nastavu', 'Direktor za nastavu'],
                    ['Predsednik saveta', 'Predsednik saveta'],
                    ['Šef katedre za ekonomiju i finansije', 'Šef katedre za ekonomiju i finansije'],
                    ['Šef katedre za menadžment i opšte pred', 'Šef katedre za menadžment i opšte pred'],
                    ['Šef katedre za pravo.', 'Šef katedre za pravo.'],
                    ['Redovni', 'Redovni'],
                    ['Vanredni', 'Vanredni'],
                    ['Docenti', 'Docenti'],
                    ['Nastavnik stranog jezika', 'Nastavnik stranog jezika'],
                    ['Asistenti', 'Asistenti'],
                    ['Saradnici', 'Saradnici'],
                    ['Van radnog odnosa', 'Van radnog odnosa'],
                ],
                'size' => 1,
                'maxitems' => 1,
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
        'group' => [
            'label' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member.group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Profesori', 'Profesori'],
                    ['Nastavnici', 'Asistenti i saradnici'],
                ],
                'default' => '',
                'behaviour' => [
                    'allowLanguageSynchronization' => false
                ],
            ],
        ],
    ],
];
