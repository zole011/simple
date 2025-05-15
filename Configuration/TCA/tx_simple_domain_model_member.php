<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:simple/Resources/Private/Language/locallang_db.xlf:tx_simple_domain_model_member',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => 'EXT:simple/Resources/Public/Icons/Extension.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, name'],
    ],
    'columns' => [
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
            ],
        ],
        'prefix' => [
            'exclude' => true,
            'label' => 'Prefix',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'prezime' => [
            'exclude' => true,
            'label' => 'Prezime',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'funkcija' => [
            'exclude' => true,
            'label' => 'Funkcija',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'zvanje' => [
            'exclude' => true,
            'label' => 'Zvanje',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'oblast' => [
            'exclude' => true,
            'label' => 'Oblast',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'konsultacije' => [
            'exclude' => true,
            'label' => 'Konsultacije',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'Email',
            'config' => [
                'type' => 'input',
                'eval' => 'email',
                'size' => 30,
            ],
        ],
        'biografija' => [
            'exclude' => true,
            'label' => 'Biografija',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
                'cols' => 40,
            ],
        ],
        'radovi' => [
            'exclude' => true,
            'label' => 'Radovi',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
                'cols' => 40,
            ],
        ],
        'udzbenici' => [
            'exclude' => true,
            'label' => 'Udžbenici',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 5,
                'cols' => 40,
            ],
        ],
        'cv' => [
            'exclude' => true,
            'label' => 'CV',
            'config' => [
                'type' => 'file',
                'allowed' => 'pdf',
                'maxitems' => 1,
            ],
        ],
        'karton' => [
            'exclude' => true,
            'label' => 'Karton',
            'config' => [
                'type' => 'file',
                'allowed' => 'pdf',
                'maxitems' => 1,
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'Slika',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'sortiranje' => [
            'exclude' => true,
            'label' => 'Sortiranje',
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
            ],
        ],

    ],
];
