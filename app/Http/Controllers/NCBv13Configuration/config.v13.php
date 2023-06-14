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
            "Family Name" => [
                "FieldTag" => "01",
                "countStringLenght" => true,
                "maxLength" => 50
            ],
            "First Name" => [
                "FieldTag" => "04",
                "countStringLenght" => true,
                "maxLength" => 30
            ],
            "Date of Birth" => [
                "FieldTag" => "07",
                "countStringLenght" => true,
                "fixedLength" => 8,
            ],
            "Customer type" => [
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
            "Address line 1" => [
                "countStringLenght" => true,
                "FieldTag" => "01",
                "maxLength" => '45',
            ],
            "Sub distinct" => [
                "countStringLenght" => true,
                "FieldTag" => "04",
                "maxLength" => '40',
            ],
            "Distinct" => [
                "countStringLenght" => true,
                "FieldTag" => "05",
                "maxLength" => '40',
            ],
            "Province" => [
                "countStringLenght" => true,
                "FieldTag" => "06",
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
                "FieldTag" => "08",
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
            "Member Code" => [
                "FieldTag" => '01',
                "countStringLenght" => true,
                'fixedLength' => 10,
                "default" => "HP22190000"
            ],
            "Member Name" => [
                "FieldTag" => '02',
                "countStringLenght" => true,
                'maxLength' => 16,
                "default" => "THUNDERF"
            ],
            "Account Number" => [
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
                "FieldTag" => '11',
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
                "maxLength" => 9,
                "default" => '000000000'
            ],
            "Amount Past Due" => [
                "FieldTag" => "14",
                "countStringLenght" => true,
                "maxLength" => 9,
                'default' => '000000000'
            ],
            "NUMBER OF DAY PAST DUE" => [
                "FieldTag" => "15",
                "countStringLenght" => true,
                "maxLength" => 3
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
                ],
                "default" => 3
            ],
            "Installment Amount" => [
                "FieldTag" => "21",
                "countStringLenght" => true,
                "maxLength" => 9
            ],
            "Installment Number Of Payment" => [
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
            "Date of Last Debt Restructring" => [
                "FieldTag" => "36",
                "countStringLenght" => true,
                "fixedLength" => 8,
                "default" => "19000101"
            ],
            "Unit Make" => [
                "FieldTag" => "40",
                "countStringLenght" => true,
                "maxLength" => 15,
                "required" => false
            ],
            "Unit model" => [
                "FieldTag" => "41",
                "countStringLenght" => true,
                "maxLength" => 15,
                "required" => false
            ],
            "End of Subject" => [
                "Segment Tag" => "",
                "fixedLength" => 6,
                "default" => 'ES02**'
            ]
        ]
    ]
];