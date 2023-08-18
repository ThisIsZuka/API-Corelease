<?php

$NCBV13 = [
    "header" => [
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
    ],
    "body" => [
        "Name Segment" => [
            "Segment Tag" => [
                "FieldTag" => "PN",
                "fixedLength" => 3,
                "countStringLenght" => true,
                "default" => "N01"
            ],
            "Family Name 1" => [
                "FieldTag" => "01",
                "countStringLenght" => true,
                "maxLength" => 50
            ],
            "Family Name 2" => [
                "FieldTag" => "02",
                "countStringLenght" => true,
                "maxLength" => 26,
                "fieldtype" => "AW"
            ],
            "First Name" => [
                "FieldTag" => "04",
                "countStringLenght" => true,
                "maxLength" => 30
            ],
            "Middle" => [
                "FieldTag" => "05",
                "countStringLenght" => true,
                "maxLength" => 30,
                "fieldtype" => "AW"
            ],
            "Marital Status" => [
                "FieldTag" => "06",
                "countStringLenght" => true,
                "maxLength" => 4,
                "fieldtype" => "AW"
            ],
            "Date of Birth" => [
                "FieldTag" => "07",
                "countStringLenght" => true,
                "fixedLength" => 8,
            ],
            "Gender" => [
                "FieldTag" => "08",
                "countStringLenght" => true,
                "fixedLength" => 1,
                "fieldtype" => "AW"
            ],
            "Title/Prefix" => [
                "FieldTag" => "09",
                "countStringLenght" => true,
                "fixedLength" => 15,
                "fieldtype" => "AW"
            ],
            "Nationality" => [
                "FieldTag" => "10",
                "countStringLenght" => true,
                "fixedLength" => 2,
                "fieldtype" => "AW"
            ],
            "Number of children" => [
                "FieldTag" => "11",
                "countStringLenght" => true,
                "fixedLength" => 2,
                "fieldtype" => "AW"
            ],
            "Spouse Name" => [
                "FieldTag" => "12",
                "countStringLenght" => true,
                "fixedLength" => 45,
                "fieldtype" => "AW"
            ],
            "Occupation" => [
                "FieldTag" => "13",
                "countStringLenght" => true,
                "fixedLength" => 1,
            ],
            "Customer Type Field" => [
                "FieldTag" => '15',
                "countStringLenght" => true,
                "fixedLength" => 1,
                "fieldtype" => "AW"
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
                "FieldTag" => "01",
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
            "ID Issue Country" => [
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
            "Address Line 1" => [
                "countStringLenght" => true,
                "FieldTag" => "01",
                "maxLength" => '45',
            ],
            "Address Line 2" => [
                "countStringLenght" => true,
                "FieldTag" => "02",
                "maxLength" => '45',
                "fieldtype" => "AW"
            ],
            "Address Line 3" => [
                "countStringLenght" => true,
                "FieldTag" => "03",
                "maxLength" => '45',
                "fieldtype" => "AW"
            ],
            "Sub district" => [
                "countStringLenght" => true,
                "FieldTag" => "04",
                "maxLength" => '40',
                "fieldtype" => "AW"
            ],
            "District" => [
                "countStringLenght" => true,
                "FieldTag" => "05",
                "maxLength" => '40',
                "fieldtype" => "AW"
            ],
            "Province" => [
                "countStringLenght" => true,
                "FieldTag" => "06",
                "maxLength" => '40',
                "fieldtype" => "AW"
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
                "FieldTag" => "08",
                "maxLength" => '10',
                "fieldtype" => "AW"
            ],
            "Telephone" => [
                "countStringLenght" => true,
                "FieldTag" => "09",
                "maxLength" => '20'
            ],
            "Telephone Type" => [
                "countStringLenght" => true,
                "FieldTag" => "10",
                "maxLength" => '1'
            ],
            "Address Type" => [
                "countStringLenght" => true,
                "FieldTag" => "11",
                "maxLength" => '1'
            ],
            "Residential Status" => [
                "countStringLenght" => true,
                "FieldTag" => "12",
                "maxLength" => '1',
                "fieldtype" => "AW"
            ],
        ],
        "Account Segment" => [
            "Segment Tag" => [
                'FieldTag' => 'TL',
                "countStringLenght" => true,
                'fixedLength' => 4,
                "default" => 'T001'
            ],
            "Current/New Member Code" => [
                "FieldTag" => '01',
                "countStringLenght" => true,
                'fixedLength' => 10,
                "default" => "HP22190000"
            ],
            "Current/New Member Name" => [
                "FieldTag" => '02',
                "countStringLenght" => true,
                'maxLength' => 16,
                "default" => "THUNDERF"
            ],
            "Current/New Account Number" => [
                "FieldTag" => '03',
                "countStringLenght" => true,
                'maxLength' => 25
            ],
            "Account Type" => [
                "FieldTag" => '04',
                "countStringLenght" => true,
                'fixedLength' => 2,
                'options' => [
                    'Personal Loan' => 1,
                    'Other Hire Purchase' => 21
                ]
            ],
            "Ownership Indicator" => [
                "FieldTag" => '05',
                "countStringLenght" => true,
                'fixedLength' => 1,
                'default' => 1
                // 'options' => [
                //     "Individual" => 1,
                //     "Supplementary Card" => 2,
                //     "Joint" => 4,
                //     "Guarantor" => 5
                // ]
            ],
            "Currency Code" => [
                "FieldTag" => '06',
                "countStringLenght" => true,
                'fixedLength' => 3,
                'default' => 'THB',
                "fieldtype" => "AW"
            ],
            "Future Use" => [
                "FieldTag" => '07',
                "countStringLenght" => true,
                'fixedLength' => 1
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
            "Date Account Closed" => [
                "FieldTag" => '10',
                "countStringLenght" => true,
                'fixedLength' => 8,
                'default' => '19000101'
            ],
            "As of Date" => [
                "FieldTag" => '11',
                "countStringLenght" => true,
                'fixedLength' => 8,
                'default' => '19000101'
            ],
            "Credit Limit/Original Loan Amount" => [
                "FieldTag" => '12',
                "countStringLenght" => true,
                "maxLength" => 9
            ],
            "Amount Owed/Credit Use" => [
                "FieldTag" => "13",
                "countStringLenght" => true,
                "maxLength" => 9,
                "default" => '000000000'
            ],
            "Amount Past Due" => [
                "FieldTag" => "14",
                "countStringLenght" => true,
                "maxLength" => 9,
                'default' => '000000000'
            ],
            "Number Of Days Past Due/Delinquency Status" => [
                "FieldTag" => "15",
                "countStringLenght" => true,
                "maxLength" => 3
            ],
            "Old Member Code" => [
                "FieldTag" => "16",
                "countStringLenght" => true,
                "maxLength" => 10,
                "fieldtype" => "AW"
            ],
            "Old Member Name" => [
                "FieldTag" => "17",
                "countStringLenght" => true,
                "maxLength" => 16,
                "fieldtype" => "AW"
            ],
            "Old Account Number" => [
                "FieldTag" => "18",
                "countStringLenght" => true,
                "maxLength" => 25
                ,"fieldtype" => "AW"
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
                // "options" => [
                //     "Unspecified" => 0,
                //     "Weekly" => 1,
                //     "Biweekly" => 2,
                //     "Monthly" => 3,
                //     "Bimonthly" => 4,
                //     "Quarterly" => 5,
                //     "Daily" => 6,
                //     "Special use" => 7,
                //     "Semi-yearly" => 8,
                //     "Yearly" => 9
                // ],
                "default" => 3
            ],
            "Installment Amount" => [
                "FieldTag" => "21",
                "countStringLenght" => true,
                "maxLength" => 9
            ],
            "Installment Number Of Payments" => [
                "FieldTag" => "22",
                "countStringLenght" => true,
                "maxLength" => 4
            ],
            "Account Status" => [
                "FieldTag" => "23",
                "countStringLenght" => true,
                "fixedLength" => 2,
                "default" => '10'
            ],
            "Loan Objective" => [
                "FieldTag" => "32",
                "countStringLenght" => true,
                "fixedLength" => 5,
                "fieldtype" => "AW"
            ],
            "Collateral 1" => [
                "FieldTag" => "33",
                "countStringLenght" => true,
                "fixedLength" => 3,
                "fieldtype" => "AW"
            ],
            "Collateral 2" => [
                "FieldTag" => "34",
                "countStringLenght" => true,
                "fixedLength" => 3,
                "fieldtype" => "AW"
            ],
            "Collateral 3" => [
                "FieldTag" => "35",
                "countStringLenght" => true,
                "fixedLength" => 3,
                "fieldtype" => "AW"
            ],
            "Date of last debt restructuring" => [
                "FieldTag" => "36",
                "countStringLenght" => true,
                "fixedLength" => 8,
                "default" => "19000101"
            ],
            "Percent payment" => [
                "FieldTag" => "37",
                "countStringLenght" => true,
                "fixedLength" => 5,
                "fieldtype" => "AW"
            ],
            "Type of credit card" => [
                "FieldTag" => "38",
                "countStringLenght" => true,
                "fixedLength" => 2,
                "fieldtype" => "AW"
            ],
            "Number of co-borrower" => [
                "FieldTag" => "39",
                "countStringLenght" => true,
                "fixedLength" => 2,
                "fieldtype" => "AW"
            ],
            "Unit Make" => [
                "FieldTag" => "40",
                "countStringLenght" => true,
                "maxLength" => 15,
                "fieldtype" => "AW"
            ],
            "Unit Model" => [
                "FieldTag" => "41",
                "countStringLenght" => true,
                "maxLength" => 15,
                "fieldtype" => "AW"
            ],
            "Credit Limit Type Flag" => [
                "FieldTag" => "42",
                "countStringLenght" => true,
                "maxLength" => 3,
                "fieldtype" => "AW"
            ],
            "End of Subject" => [
                "Segment Tag" => "",
                "fixedLength" => 6,
                "default" => 'ES02**'
            ]
        ]
    ]
];