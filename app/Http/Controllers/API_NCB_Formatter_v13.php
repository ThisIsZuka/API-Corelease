<?php

namespace App\Http\Controllers;

use App\Http\Controllers\files\file;
use App\Http\Controllers\NCBFormatter\NCB_FORMATTER;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;

class API_NCB_FORMATTER_v13 extends NCB_FORMATTER {
    public function __construct()
    {
        parent::__construct();
        $this->version = '13';
        $this->pathfile = '/file_location/report_ncb/' . $this->version;
        $this->file = new file($this->pathfile);
        $this->file->check_folder_is_exsist($this->pathfile, 'public');

        $this->tudf_header_section = [
            'version' => [
                "fixedLength" => 2
            ],
            'membercode' => [
                "freespace" => true,
                "position" => 'postfix',
                "fixedLength" => 10
            ],
            'membername' => [
                "freespace" => true,
                "position" => 'postfix',
                "fixedLength" => 16
            ],
            'cycle_identification' => [
                "freespace" => true,
                "fixedLength" => 2
            ],
            'as_of_date' => [
                "fixedLength" => 8
            ],
            'password' => [
                "fixedLength" => 8
            ],
            'futureuse' => [
                "zerofill" => true,
                "position" => 'prefix',
                "fixedLength" => 2
            ],
            'memberdata' => [
                "freespace" => true,
                "position" => 'prefix',
                "fixedLength" => 40
            ],
            'tracing_number' => [
                "zerofill" => true,
                "position" => 'prefix',
                "fixedLength" => 8
            ]
        ];

        $this->tudf_body_section = [
            [
                "Name Segment" => [
                    "Segment Tag" => [
                        "FieldTag" => "PN",
                        "fixedLength" => 3,
                        "countStringLenght" => true,
                        "default" => "N01"
                    ],
                    "FamilyName1" => [
                        "FieldTag" => "01",
                        "countStringLenght" => true,
                        "maxLength" => 50
                    ],
                    "FirstName" => [
                        "FieldTag" => "04",
                        "countStringLenght" => true,
                        "maxLength" => 30
                    ],
                    "DateOfBrith" => [
                        "FieldTag" => "07",
                        "countStringLenght" => true,
                        "fixedLength" => 8,
                    ],
                    "CustomerType" => [
                        "FieldTag" => '15',
                        "countStringLenght" => true,
                        "fixedLength" => 1
                    ]
                ],
                "ID Segment" => [
                    "Segment Tag" => [
                        "FieldTag" => "ID",
                        "countStringLenght" => true,
                        "fixedLength" => 3,
                        'default' => "ID1"
                    ],
                    "ID Type" => [
                        "FieldTag" => "ID",
                        "countStringLenght" => true,
                        "fixedLength" => 2,
                        "options" => [
                            "Citizen ID" => '01',
                            "Civil Servant ID" => '02', 
                            "Alien" => '05',
                            "Foreign Passport/ID" => '07',
                            "Other" => "09"
                        ]
                    ],
                    "ID Number" => [
                        "FieldTag" => "02",
                        "maxLength" => 20,
                        "countStringLenght" => true
                    ],
                    "Issue Country" => [
                        "FieldTag" => "03",
                        "fixedLength" => 2,
                        "countStringLenght" => true,
                        "fieldtype" => "AW"
                    ]
                ],
                "Address Segment" => [
                    "Segment Tag" => [
                        "FieldTag" => "PA",
                        "countStringLenght" => true,
                        "maxLength" => 3,
                        "default" => "A01"
                    ],
                    "Address1" => [
                        "countStringLenght" => true,
                        "FieldTag" => "01",
                        "maxLength" => '45',
                    ],
                    "Address2" => [
                        "countStringLenght" => true,
                        "FieldTag" => "02",
                        "maxLength" => '45',
                    ],
                    "Address2" => [
                        "countStringLenght" => true,
                        "FieldTag" => "03",
                        "maxLength" => '45',
                    ],
                    "Subdistinct" => [
                        "countStringLenght" => true,
                        "FieldTag" => "04",
                        "maxLength" => '40',
                    ],
                    "Distinct" => [
                        "countStringLenght" => true,
                        "FieldTag" => "04",
                        "maxLength" => '40',
                    ],
                    "Province" => [
                        "countStringLenght" => true,
                        "FieldTag" => "04",
                        "maxLength" => '40',
                    ],
                    "Country" => [
                        "countStringLenght" => true,
                        "FieldTag" => "07",
                        "maxLength" => '4',
                        "FieldType" => "AW",
                        "default" => "TH"
                    ],
                    "Postal Code" => [
                        "countStringLenght" => true,
                        "FieldTag" => "04",
                        "maxLength" => '10'
                    ]
                ],
                "Account Segment" => [
                    "Segment Tag" => [
                        'FieldTag' => 'TL',
                        "countStringLenght" => true,
                        'fixedLength' => 4,
                        "default" => 'T001'
                    ],
                    "membercode" => [
                        "FieldTag" => '01',
                        "countStringLenght" => true,
                        'fixedLength' => 10
                    ],
                    "membername" => [
                        "FieldTag" => '02',
                        "countStringLenght" => true,
                        'maxLength' => 16
                    ],
                    "Account Number" => [
                        "FieldTag" => '03',
                        "countStringLenght" => true,
                        'maxLength' => 25
                    ],
                    "Account Type" => [
                        "FieldTag" => '04',
                        "countStringLenght" => true,
                        'fixedLength' => 2
                    ],
                    "Ownership Indicator" => [
                        "FieldTag" => '05',
                        "countStringLenght" => true,
                        'fixedLength' => 1,
                        'options' => [
                            "กู้เดี่ยว" => 1,
                            "บัตรเสริม" => 2,
                            "กู้ร่วม" => 4
                        ]
                    ],
                    "Currency Code" => [
                        "FieldTag" => '06',
                        "countStringLenght" => true,
                        'fixedLength' => 3,
                        'default' => 'THB'
                    ],
                    "Date Account Opened" => [
                        "FieldTag" => '08',
                        "countStringLenght" => true,
                        'fixedLength' => 8,
                        'default' => '19000101'
                    ],
                    "Date Of Last Payment" => [
                        "FieldTag" => '09',
                        "countStringLenght" => true,
                        'fixedLength' => 8,
                        'default' => '19000101'
                    ],
                    "Data Account Closed" => [
                        "FieldTag" => '10',
                        "countStringLenght" => true,
                        'fixedLength' => 8,
                        'default' => '19000101'
                    ],
                    "As of Date" => [
                        "FieldTag" => '10',
                        "countStringLenght" => true,
                        'fixedLength' => 8,
                        'default' => '19000101'
                    ],
                    "Credit Limit" => [
                        "FieldTag" => '12',
                        "countStringLenght" => true,
                        "maxLength" => 9
                    ],
                    "Amount Owned" => [
                        "FieldTag" => "13",
                        "countStringLenght" => true,
                        "maxLength" => 9
                    ],
                    "Amount Past Due" => [
                        "FieldTag" => "14",
                        "countStringLenght" => true,
                        "maxLength" => 9
                    ],
                    "Number Of Days Past Due" => [
                        "FieldTag" => "15",
                        "countStringLenght" => true,
                        "maxLength" => 3,
                        "options" => [
                            '000' => [0, 30],
                            '001' => [31, 60],
                            '002' => [61, 90],
                            '003' => [91, 120],
                            '004' => [121, 150],
                            '005' => [151, 180],
                            '006' => [181, 210],
                            '007' => [211, 240],
                            '008' => [241, 270],
                            '009' => [271, 300],
                            '__F' => [301]
                        ]
                    ],
                    "Default Date" => [
                        "FieldTag" => "19",
                        "countStringLenght" => true,
                        "fixedLength" => 8
                    ],
                    "Installment Frequency" => [
                        "FieldTag" => "20",
                        "countStringLenght" => true,
                        "fixedLength" => 1,
                        "options" => [
                            "Unspecified" => 0,
                            "Weekly" => 1,
                            "Biweekly" => 2,
                            "Monthly" => 3,
                            "Bimonthly" => 4,
                            "Quarterly" => 5,
                            "Daily" => 6,
                            "Special use" => 7,
                            "Semi-yearly" => 8,
                            "Yearly" => 9
                        ]
                    ],
                    "Installment Amount" => [
                        "FieldTag" => "21",
                        "countStringLenght" => true,
                        "maxLength" => 9
                    ],
                    "Installment Amount of Payment" => [
                        "FieldTag" => "22",
                        "countStringLenght" => true,
                        "fixedLength" => 4
                    ],
                    "Account Status" => [
                        "FieldTag" => "23",
                        "countStringLenght" => true,
                        "fixedLength" => 2
                    ],
                    "Date of Last Debt Restructring" => [
                        "FieldTag" => "36",
                        "countStringLenght" => true,
                        "fixedLength" => 8
                    ],
                    "Unit Make" => [
                        "FieldTag" => "40",
                        "countStringLenght" => true,
                        "maxLength" => 15
                    ],
                    "Unit model" => [
                        "FieldTag" => "41",
                        "countStringLenght" => true,
                        "maxLength" => 15
                    ]
                ],
                "End of Subject" => [
                    "Segment Tag" => "",
                    "fixedLength" => 6,
                    "default" => 'ES02**'
                ]
            ]
        ];

        $this->getData();
    }
    public function getReport($filetype = '') {
        if ($filetype != '') {
            $this->getData()->generate($filetype);
        }

        return $this;
    }

    public function generate($filetype = 'txt', $encrypt = true) {
        // $file = '';
        // if ($filetype == 'txt') {
            $this->getFormatter();
        // } else {
        //     $this->getFormatter();
        //     $encrypt = false;
        // }

        // if ($encrypt) {
        //     $this->encrypt();
        // }

        return $this;
    }

    public function encrypt($encrypt = 'rar') {

    }

    public function toString() {
        return ["filename" => $this->filename, "data" => $this->getData_with_head()];
    }
}