<?php
//define('WIPREFNO','IOR:BSG-1/0420/CADQ-1/PI-1');
define('FILE_EXTENSIONS', array("jpg", "jpeg", "png", "gif", "bmp", "pdf", "PDF", "doc", "docx", "xls", "xlsx", "txt", "zip", "rar"));
define('DWN_FILE_EXTENSIONS', array("doc", "docx", "xls", "xlsx", "zip", "rar"));
define('ARRCOMPANYTABLETYPE', serialize(array('1' => 'Table Type 1', '2' => "Table Type 2")));
define('ARRSTATUS', serialize(array('1' => 'Active', '2' => "Inactive")));
define('PROFARRSTATUS', serialize(array('1' => 'Active', '2' => "Inactive",'3'=>"Standby")));
define('ARRVENDORCATEGORY', serialize(array('1' => 'Nominated', '2' => "General")));
define('COMMONPWD', 'Password123');
define("ARRCOUNTRYLIST", serialize(array('1' => 'AFGHANISTAN', '2' => 'ALAND ISLANDS', '3' => 'ALBANIA', '4' => 'ALGERIA', '5' => 'AMERICAN SAMOA', '6' => 'ANDORRA', '7' => 'ANGOLA', '8' => 'ANGUILLA', '9' => 'ANTARCTICA', '10' => 'ANTIGUA AND BARBUDA', '11' => 'ARGENTINA', '12' => 'ARMENIA', '13' => 'ARUBA', '14' => 'AUSTRALIA', '15' => 'AUSTRIA', '16' => 'AZERBAIJAN', '17' => 'BAHAMAS', '18' => 'BAHRAIN', '19' => 'BANGLADESH', '20' => 'BARBADOS', '21' => 'BELARUS', '22' => 'BELGIUM', '23' => 'BELIZE', '24' => 'BENIN', '25' => 'BERMUDA', '26' => 'BHUTAN', '27' => 'BOLIVIA', '28' => 'BONAIRE', '29' => 'BOSNIA AND HERZEGOVINA', '30' => 'BOTSWANA', '31' => 'BOUVET ISLAND', '32' => 'BRAZIL', '33' => 'BRITISH INDIAN OCEAN TERRITORY', '34' => 'BRUNEI DARUSSALAM', '35' => 'BULGARIA', '36' => 'BURKINA FASO', '37' => 'BURUNDI', '38' => 'CAMBODIA', '39' => 'CAMEROON', '40' => 'CANADA', '41' => 'CAPE VERDE', '42' => 'CAYMAN ISLANDS', '43' => 'CENTRAL AFRICAN REPUBLIC', '44' => 'CHAD', '45' => 'CHILE', '46' => 'CHINA', '47' => 'CHRISTMAS ISLAND', '48' => 'COCOS (KEELING) ISLANDS', '49' => 'COLOMBIA', '50' => 'COMOROS', '51' => 'CONGO', '52' => 'CONGO', '53' => 'COOK ISLANDS', '54' => 'COSTA RICA', '55' => "COTE D IVOIRE", '56' => 'CROATIA', '57' => 'CUBA', '58' => 'CURACAO', '59' => 'CYPRUS', '60' => 'CZECH REPUBLIC', '61' => 'DENMARK', '62' => 'DJIBOUTI', '63' => 'DOMINICA', '64' => 'DOMINICAN REPUBLIC', '65' => 'ECUADOR', '66' => 'EGYPT', '67' => 'EL SALVADOR', '68' => 'EQUATORIAL GUINEA', '69' => 'ERITREA', '70' => 'ESTONIA', '71' => 'ETHIOPIA', '72' => 'FALKLAND ISLANDS (MALVINAS)', '73' => 'FAROE ISLANDS', '74' => 'FIJI', '75' => 'FINLAND', '76' => 'FRANCE', '77' => 'FRENCH GUIANA', '78' => 'FRENCH POLYNESIA', '79' => 'FRENCH SOUTHERN TERRITORIES', '80' => 'GABON', '81' => 'GAMBIA', '82' => 'GEORGIA', '83' => 'GERMANY', '84' => 'GHANA', '85' => 'GIBRALTAR', '86' => 'GREECE', '87' => 'GREENLAND', '88' => 'GRENADA', '89' => 'GUADELOUPE', '90' => 'GUAM', '91' => 'GUATEMALA', '92' => 'GUERNSEY', '93' => 'GUINEA', '94' => 'GUINEA-BISSAU', '95' => 'GUYANA', '96' => 'HAITI', '97' => 'HEARD ISLAND AND MCDONALD ISLANDS', '98' => 'HOLY SEE (VATICAN CITY STATE)', '99' => 'HONDURAS', '100' => 'HONG KONG', '101' => 'HUNGARY', '102' => 'ICELAND', '103' => 'INDIA', '104' => 'INDONESIA', '105' => 'IRAN', '106' => 'IRAQ', '107' => 'IRELAND', '108' => 'ISLE OF MAN', '109' => 'ISRAEL', '110' => 'ITALY', '111' => 'JAMAICA', '112' => 'JAPAN', '113' => 'JERSEY', '114' => 'JORDAN', '115' => 'KAZAKHSTAN', '116' => 'KENYA', '117' => 'KIRIBATI', '118' => 'KOREA', '119' => 'KOREA', '120' => 'KUWAIT', '121' => 'KYRGYZSTAN', '122' => "LAO PEOPLES DEMOCRATIC REPUBLIC", '123' => 'LATVIA', '124' => 'LEBANON', '125' => 'LESOTHO', '126' => 'LIBERIA', '127' => 'LIBYA', '128' => 'LIECHTENSTEIN', '129' => 'LITHUANIA', '130' => 'LUXEMBOURG', '131' => 'MACAO', '132' => 'MACEDONIA', '133' => 'MADAGASCAR', '134' => 'MALAWI', '135' => 'MALAYSIA', '136' => 'MALDIVES', '137' => 'MALI', '138' => 'MALTA', '139' => 'MARSHALL ISLANDS', '140' => 'MARTINIQUE', '141' => 'MAURITANIA', '142' => 'MAURITIUS', '143' => 'MAYOTTE', '144' => 'MEXICO', '145' => 'MICRONESIA', '146' => 'MOLDOVA', '147' => 'MONACO', '148' => 'MONGOLIA', '149' => 'MONTENEGRO', '150' => 'MONTSERRAT', '151' => 'MOROCCO', '152' => 'MOZAMBIQUE', '153' => 'MYANMAR', '154' => 'NAMIBIA', '155' => 'NAURU', '156' => 'NEPAL', '157' => 'NETHERLANDS', '158' => 'NEW CALEDONIA', '159' => 'NEW ZEALAND', '160' => 'NICARAGUA', '161' => 'NIGER', '162' => 'NIGERIA', '163' => 'NIUE', '164' => 'NORFOLK ISLAND', '165' => 'NORTHERN MARIANA ISLANDS', '166' => 'NORWAY', '167' => 'OMAN', '168' => 'PAKISTAN', '169' => 'PALAU', '170' => 'PALESTINE', '171' => 'PANAMA', '172' => 'PAPUA NEW GUINEA', '173' => 'PARAGUAY', '174' => 'PERU', '175' => 'PHILIPPINES', '176' => 'PITCAIRN', '177' => 'POLAND', '178' => 'PORTUGAL', '179' => 'PUERTO RICO', '180' => 'QATAR', '181' => 'REUNION', '182' => 'ROMANIA', '183' => 'RUSSIAN FEDERATION', '184' => 'RWANDA', '185' => 'SAINT BARTHELEMY', '186' => 'SAINT HELENA', '187' => 'SAINT KITTS AND NEVIS', '188' => 'SAINT LUCIA', '189' => 'SAINT MARTIN (FRENCH PART)', '190' => 'SAINT PIERRE AND MIQUELON', '191' => 'SAINT VINCENT AND THE GRENADINES', '192' => 'SAMOA', '193' => 'SAN MARINO', '194' => 'SAO TOME AND PRINCIPE', '195' => 'SAUDI ARABIA', '196' => 'SENEGAL', '197' => 'SERBIA', '198' => 'SEYCHELLES', '199' => 'SIERRA LEONE', '200' => 'SINGAPORE', '201' => 'SINT MAARTEN (DUTCH PART)', '202' => 'SLOVAKIA', '203' => 'SLOVENIA', '204' => 'SOLOMON ISLANDS', '205' => 'SOMALIA', '206' => 'SOUTH AFRICA', '207' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', '208' => 'SOUTH SUDAN', '209' => 'SPAIN', '210' => 'SRI LANKA', '211' => 'SUDAN', '212' => 'SURINAME', '213' => 'SVALBARD AND JAN MAYEN', '214' => 'SWAZILAND', '215' => 'SWEDEN', '216' => 'SWITZERLAND', '217' => 'SYRIAN ARAB REPUBLIC', '218' => 'TAIWAN', '219' => 'TAJIKISTAN', '220' => 'TANZANIA', '221' => 'THAILAND', '222' => 'TIMOR-LESTE', '223' => 'TOGO', '224' => 'TOKELAU', '225' => 'TONGA', '226' => 'TRINIDAD AND TOBAGO', '227' => 'TUNISIA', '228' => 'TURKEY', '229' => 'TURKMENISTAN', '230' => 'TURKS AND CAICOS ISLANDS', '231' => 'TUVALU', '232' => 'UGANDA', '233' => 'UKRAINE', '234' => 'UNITED ARAB EMIRATES', '235' => 'UNITED KINGDOM', '236' => 'UNITED STATES', '237' => 'UNITED STATES MINOR OUTLYING ISLANDS', '238' => 'URUGUAY', '239' => 'UZBEKISTAN', '240' => 'VANUATU', '241' => 'VENEZUELA', '242' => 'VIET NAM', '243' => 'VIRGIN ISLANDS', '244' => 'WALLIS AND FUTUNA', '245' => 'WESTERN SAHARA', '246' => 'YEMEN', '247' => 'ZAMBIA', '248' => 'ZIMBABWE')));
define("ARRMONTHLIST", serialize(array("1" => "Jan", "2" => "Feb", "3" => "Mar", "4" => "Apr", "5" => "May", "6" => "Jun", "7" => "Jul", "8" => "Aug", "9" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec")));
define("COMPANYNAME", "AZIBO INFOTECH PRIVATE LIMITED");
$COMPANYADDRESS1 = "24A, Aiswariyam Jeyam Gardens,Kaliammal Colony, Thudiyalur, Coimbatore - 641 034";
define("COMPANYADDRESS1", $COMPANYADDRESS1);
$WEBSITENAME = "app.knit2020.com";
define("WEBSITENAME", $WEBSITENAME);
$APPDOMAINNAME = "knit2020.com";
define("APPDOMAINNAME", $APPDOMAINNAME);
/*Only Space and dot is allowed in user type (Caps can be used)
 * */
define('ARRUSERTYPE', serialize(array(
    '1' => 'User Admin', '2' => 'Management', '3' => 'Merchandising Dept.',
    '4' => 'CAD Dept.', '5' => 'Sampling Dept.', '6' => 'Fabric Dept.',
    '7' => 'Purchase Dept.', '8' => 'BOM Store', '9' => 'Production Dept.',
    '10' => 'Lab Dept.', '11' => 'Q.A Dept.', '12' => 'Finance Dept.', '13' => 'Doc & Loc Dept.', 
    '14' => 'Fabric Store - 1','16' => 'Fabric Store - 2','15'=>'Planning Dept.'
))); // here company admin renamed to User Admin,Yarn Store renamed to Fabric Store,Administrator renamed to Business Admin,'0' => 'Business Admin', removed from here
// on 11-01-24 renamed fabric store to fabric store-1
define('ARRUSERTYPENEW', serialize(array(
    '2' => 'Management', '3' => 'Merchandising Dept.',
    '4' => 'CAD Dept.', '5' => 'Sampling Dept.', '6' => 'Fabric Dept.',
    '7' => 'Purchase Dept.', '8' => 'BOM Store', '9' => 'Production Dept.',
    '10' => 'Lab Dept.', '11' => 'Q.A Dept.', '12' => 'Finance Dept.', '13' => 'Doc & Loc Dept.', 
    '14' => 'Fabric Store - 1','16' => 'Fabric Store - 2','15'=>'Planning Dept.'
))); // '1' => 'User Admin', removed due to subscriber login creation
define('BARRUSERTYPE', serialize(array(
    '0' => 'Business Admin','16' => 'Marketing Dept.'
)));
define('UT_SHORT_FORM', array('2' => 'MAN', '3' => 'MER', '4' => 'CAD', '5' => 'SAM', '6' => 'FAB', '7' => 'PUR', '8' => 'BOM', '9' => 'PRO', '10' => 'LAB', '11' => 'Q&A', '12' => 'FIN', '13' => 'D&L','15'=>'PLA'));
define('ARRPROFILEPERMISSION', serialize(array('1' => 'Admin', '2' => "Employee")));
define('ARRPLANTMACHINETYPE', serialize(array('1' => 'Machinery', '2' => "Table")));
//define('ARRCOMPANYBUSINESSTYPE', serialize(array('1' => 'Business Type 1', '2' => "Business Type 2", '3' => "Business Type 3")));
define('ARRCOMPANYBUSINESSTYPE', serialize(array('1' => 'Proprietary', '2' => "Partnership", '3' => "PVT. Ltd.", "4" => "Ltd.", "5" => "Govt. Sector")));
define('ARRCOMPANYFACTORYOWNERSHIP', serialize(array('1' => 'Type 1', '2' => "Type 2", '3' => "Type 3")));
define('ARRCOMPANYMACHINEPLANTTYPE', serialize(array('1' => 'Machine Type', '2' => "Table Type")));
define('ARRCOMPANYMACHINETYPE', serialize(array('1' => 'Machine Type 1', '2' => "Machine Type 2")));
define("CNFCADMIN", "cadmin/");
define("CNFBADMIN", "badmin/");
define("CNFCOMPANY", "company/");
define("CNFREQUEST", "request/");
define("CNFJSCSSRANDNO", mt_rand());
//Database Config
//chamge db name by mdv from knit2020
// define("DBNAME", "rsgtrend_final");
// define("DBNAME", "rsgtrend_demo");
//define("DBNAME", "rsgtrend_demo3");
define("DBNAME", "rsgtrend_demo4");
//In Percentage %
define("tax", 18);
define("BOMPURCHASETAXTYPE", serialize(array('1' => 'SGST / CGST RATE', '2' => 'IGST RATE', '3' => 'IMPORT DUTY')));
define('ordercode', "BSG-");
define('MERCHANTCODE', ordercode . 'MERCHANT/');
define('IORNUMBER', 'IOR');
define('ISRNUMBER', 'ISR');
//Order Process
define('ORDERAUTHORITYTYPE', serialize(array('1' => 'Approval', '2' => 'Testing', '3' => 'Inspection')));
define('ORDERPACKINGTYPE', serialize(array('Set', 'Single piece')));
define('ORDERMODEOFSHIPMENT', serialize(array('Air', 'Sea', 'Rail', 'Road')));
define('ORDERSOURCEDETAIL', serialize(array('Nominated', 'Fac. Source')));
define('ARRBOMSRCCAT', serialize(array('New Dev.', 'Revised', 'In-line')));
define('ARRYESNO', serialize(array('Yes', 'No')));
//$ARRCURRENCYLIST = array('0'=>'-','1'=>'AFN','2'=>'EUR','3'=>'ALL','4'=>'DZD','5'=>'USD','6'=>'AOA','7'=>'XCD','8'=>'ARS','9'=>'AMD','10'=>'AWG','11'=>'AUD','12'=>'AZN','13'=>'BSD','14'=>'BHD','15'=>'BDT','16'=>'BBD','17'=>'BYN','18'=>'BZD','19'=>'XOF','20'=>'BMD','21'=>'INR','22'=>'BTN','23'=>'BOB','24'=>'BOV','25'=>'BAM','26'=>'BWP','27'=>'NOK','28'=>'BRL','29'=>'BND','30'=>'BGN','31'=>'BIF','32'=>'CVE','33'=>'KHR','34'=>'XAF','35'=>'CAD','36'=>'KYD','37'=>'CLP','38'=>'CLF','39'=>'CNY','40'=>'COP','41'=>'COU','42'=>'KMF','43'=>'CDF','44'=>'NZD','45'=>'CRC','46'=>'HRK','47'=>'CUP','48'=>'CUC','49'=>'ANG','50'=>'CZK','51'=>'DKK','52'=>'DJF','53'=>'DOP','54'=>'EGP','55'=>'SVC','56'=>'ERN','57'=>'ETB','58'=>'FKP','59'=>'FJD','60'=>'XPF','61'=>'GMD','62'=>'GEL','63'=>'GHS','64'=>'GIP','65'=>'GTQ','66'=>'GBP','67'=>'GNF','68'=>'GYD','69'=>'HTG','70'=>'HNL','71'=>'HKD','72'=>'HUF','73'=>'ISK','74'=>'IDR','75'=>'XDR','76'=>'IRR','77'=>'IQD','78'=>'ILS','79'=>'JMD','80'=>'JPY','81'=>'JOD','82'=>'KZT','83'=>'KES','84'=>'KPW','85'=>'KRW','86'=>'KWD','87'=>'KGS','88'=>'LAK','89'=>'LBP','90'=>'LSL','91'=>'ZAR','92'=>'LRD','93'=>'LYD','94'=>'CHF','95'=>'MOP','96'=>'MKD','97'=>'MGA','98'=>'MWK','99'=>'MYR','100'=>'MVR','101'=>'MRU','102'=>'MUR','103'=>'XUA','104'=>'MXN','105'=>'MXV','106'=>'MDL','107'=>'MNT','108'=>'MAD','109'=>'MZN','110'=>'MMK','111'=>'NAD','112'=>'NPR','113'=>'NIO','114'=>'NGN','115'=>'OMR','116'=>'PKR','117'=>'PAB','118'=>'PGK','119'=>'PYG','120'=>'PEN','121'=>'PHP','122'=>'PLN','123'=>'QAR','124'=>'RON','125'=>'RUB','126'=>'RWF','127'=>'SHP','128'=>'WST','129'=>'STN','130'=>'SAR','131'=>'RSD','132'=>'SCR','133'=>'SLL','134'=>'SGD','135'=>'XSU','136'=>'SBD','137'=>'SOS','138'=>'SSP','139'=>'LKR','140'=>'SDG','141'=>'SRD','142'=>'SZL','143'=>'SEK','144'=>'CHE','145'=>'CHW','146'=>'SYP','147'=>'TWD','148'=>'TJS','149'=>'TZS','150'=>'THB','151'=>'TOP','152'=>'TTD','153'=>'TND','154'=>'TRY','155'=>'TMT','156'=>'UGX','157'=>'UAH','158'=>'AED','159'=>'USN','160'=>'UYU','161'=>'UYI','162'=>'UYW','163'=>'UZS','164'=>'VUV','165'=>'VES','166'=>'VND','167'=>'YER','168'=>'ZMW','169'=>'ZWL','170'=>'XBA','171'=>'XBB','172'=>'XBC','173'=>'XBD','174'=>'XTS','175'=>'XXX','176'=>'XAU','177'=>'XPD','178'=>'XPT','179'=>'XAG');
//define('ARRCURRENCYLIST',serialize(array('Select','Indian Rupee (INR)','Singapore Dollar (SGD)','Hong Kong Dollar (HKD)',
//'Malaysian Ringgit (MYR)','US dollar (USD)','Euro (EUR)','Japanese yen (JPY)','Pound sterling (GBP)','Australian dollar (AUD)',
//'Canadian dollar (CAD)','Swiss franc (CHF)','Chinese renminbi (CNH)','Swedish krona (SEK)','New Zealand dollar (NZD)')));
define('ARRCURRENCYLIST', serialize(array('Select', 'INR', 'SGD', 'HKD', 'MYR', 'USD', 'EUR', 'JPY', 'GBP', 'AUD', 'CAD', 'CHF', 'CNH', 'SEK', 'NZD')));
define('BOMPI_MODEOFPAYMENT', serialize(array("-", "Cheque", "DD", "RTGS", "SWIFT")));
define('ARRENQUIRYTYPE', array('1' => 'Development', '2' => 'Order Confirmation', '3' => 'P.O. Received'));
define('ARRISRIOR', serialize(array('1' => 'ISR', '2' => 'IOR')));
define('ARRPCSSET', serialize(array('1' => 'Pcs.', '2' => 'Set')));
define('JXL_PCS_SET', serialize(array('Pcs.', 'Set')));
define('ARRCURRENTSTATUS', serialize(array('1' => 'Approval', '2' => 'Processing', '3' => 'Completed')));
define('ORDERENQUIRYSTATUS', serialize(array('0' => '-', '1' => 'PENDING', '2' => 'APPROVED', '3' => 'DECLINED', '4' => 'PENDING-RR')));
define('REQUESTSTATUSARR', serialize(array('0' => '-', '1' => 'PENDING', '2' => 'ACCEPT', '3' => 'DECLINE', '4' => 'PENDING-RR')));
//Master Pages
define('ARRFABRICTYPE', serialize(array('1' => 'Knit', '2' => 'Woven')));
/*Normal - 120 Hrs.</option>
Regular - 72 Hrs.</option>
Priority - 48 Hrs.</option>
H. Priority - 24 Hrs.</option>
Immed. - 2 Hrs.</option>*/

define('ARRREQUESTTYPE', serialize(array('0' => '-', '1' => 'Normal - 120 Hrs.', '2' => 'Regular - 75 Hrs.', '3' => 'Priority - 48 Hrs.',
    '4' => 'H. Priority - 24 Hrs.', '5' => 'Immediate - 2 Hrs.')));

define('ARRCADCATEGORY', serialize(array('New', 'In-line', 'Revised')));
define('ARRKNITTINGTYPE', serialize(array('', 'TUBULAR', 'OPEN WIDTH')));
define('ARRDYEINGTYPE', serialize(array('-', 'FD', 'YDS', 'YDJ', 'SDB')));
define('COMPACTINGTYPE', serialize(array('', 'TUBULAR', 'OPEN WIDTH')));
define('SAMPLEATTACHMENTSTATUS', serialize(array('1' => '', '2' => '', '3' => '')));
define('SAMPLEATTACHMENTSTATUS2', serialize(array('1' => '', '2' => '', '3' => 'N.A.')));
define('ARRSHIPMENTSTATUS', serialize(array('1' => 'PENDING', '2' => 'SCHEDULED', '3' => 'GOODS SHIPPED', '4' => 'SHIPMENT RESCHEDULED (1)', '5' => 'SHIPMENT RESCHEDULED (2)',
    '6' => 'SHIPMENT RESCHEDULED (3)', '7' => 'SHIPMENT RESCHEDULED (4)', '8' => 'SHIPMENT RESCHEDULED (5)')));
define('UPLOADSFDRNAME', 'uploads');
define('UPLOADS_SLASH', FCPATH . 'uploads' . DIRECTORY_SEPARATOR);
/* 7 MB */
define('MAXUPLSIZE',7340032);
//check removed or not requirement master page
$ArrBomReq = array('0' => '-', '1' => 'BOM Article 1', '2' => 'BOM Article 2', '3' => 'BOM Article 1 Shortages', '4' => 'BOM Article 2 Shortages');
$ArrSamRequirement = array('Dev. Sample', 'Fit Sample', 'P.P. Sample', 'Size Set', 'TOP',
    'Proto Sample', 'Sales Man Sample', 'Photo Sample');
$ArrCadReq = array('Mini Marker', 'Bit Marker', 'Pattern', 'Lay Marker', 'Others');
define('ARRCADREQUIREMENT', $ArrCadReq);
define('ARRSAMPLEREQREQUIREMENT', serialize($ArrSamRequirement));
define('ARRBOMREQUIREMENT', serialize($ArrBomReq));
$ArrAllReq = array_merge($ArrBomReq, $ArrSamRequirement, $ArrCadReq);
define('ALLREQUIREMENTS', serialize($ArrAllReq));
define('ARR_BOM_PURCHASE_REQUEST_PURPOSE', array('Sampling', 'Production'));
//check removed or not requirement master page
define('BOMEXP', serialize(array('1' => 'Documentation Charges', '2' => 'C & F Charges', '3' => 'Courier Charges', '4' => 'Transportation Charges', '5' => 'Handling Charges', '6' => 'Other Charges')));
define('ARRUNITOFMEASURE', serialize(array('1' => 'Inches', '2' => 'Feet', '3' => 'Cms.', '4' => 'Millimetres', '5' => 'Meter', '6' => 'Nos.', '7' => 'Gross', '8' => 'Dozens',
    '9' => 'Grams', '10' => 'Kilograms', '11' => 'Ounces', '12' => 'Pounds', '13' => 'Gallons', '14' => 'Liters')));
define('ARRLOTAPPRUSERTYPE', serialize(array('4' => 'itemverifyauthstatus', '9' => 'qtyverifyauthstatus', '12' => 'qualityanaauthstatus', '8' => 'invoiceverifyauthstatus',
    '1' => 'itemreadyauthstatus')));
/* PREFIX */
define('CADINDENT_REFNO_PREFIX', 'CMI-');
define('FABINDENT_REFNO_PREFIX', 'FMI-');
define('BOMINDENT_REFNO_PREFIX', 'BMI-');
define('BOMPURIND_PREFIX', 'BPI-');
define('CAD_REFNO_PREFIX', 'CAD-Ref:');
define('SAM_REFNO_PREFIX', 'SAM-Ref/');
define('CADQNO_PREFIX', 'CQ-');
define('SAMQNO_PREFIX', 'SQ-');
define('BOMQNO_PREFIX', 'BQ-');
/* PREFIX ENDS */
$ArrSizeChart = array("1" => "Standard Size", "2" => "Custom Size");
define('ARR_SIZE_CHART', $ArrSizeChart);
$ArrStdSizes = array("1" => array("1" => "P44", "2" => "P50", "3" => "PR", "4" => "NB", "5" => "3M", "6" => "6M", "7" => "9M", "8" => "12M", "9" => "18M", "10" => "24M",
    "11" => "3T", "12" => "4T", "13" => "XXS", "14" => "XS", "15" => "S", "16" => "M", "17" => "L", "18" => "XL", "19" => "XXL", "20" => "2XL", "21" => "3XL", "22"
    => "4XL", "23" => "1", "24" => "2", "25" => "3", "26" => "4", "27" => "5", "28" => "6", "29" => "6X", "30" => "7", "31" => "8", "32" => "9", "33" => "10",
    "34" => "11", "35" => "12", "36" => "13", "37" => "14", "38" => "15", "39" => "16", "40" => "18", "41" => "20", "42" => "22", "43" => "24", "44" => "26", "45"
    => "28", "46" => "30", "47" => "32", "48" => "34", "49" => "36", "50" => "38", "51" => "40", "52" => "42", "53" => "44", "54" => "46", "55" => "48", "56"
    => "50", "57" => "52", "58" => "54", "59" => "56", "60" => "58", "61" => "60", "62" => "70", "63" => "80", "64" => "90", "65" => "100", "66" => "110",
    "67" => "120", "68" => "130", "69" => "140", "70" => "150", "71" => "160", "72" => "170"));
define('ARR_STD_SIZE',$ArrStdSizes);
//$ArrMasterChartInfo = array("1"=>"Size Chart 1","2"=>"Size Chart 2","3"=>"Size Chart 3","4"=>"Custom Size Chart");
$ArrSizeChartDetails = array("1" => array("1" => "XXS", "2" => "XS", "3" => "S", "4" => "M", "5" => "L", "6" => "XL", "7" => "XXL", "8" => "2XL", "9" => "3XL", "10" => "4XL"), "2" => array("1" => "P44", "2" => "P50", "3" => "PR", "4" => "NB", "5" => "3M", "6" => "6M", "7" => "9M", "8" => "12M", "9" => "18M", "10" => "24M", "11" => "3T", "12" => "4T"), "3" => array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "6X", "8" => "7", "9" => "8", "10" => "9", "11" => "10", "12" => "11", "13" => "12"), '4' => array("1" => "13", "2" => "14", "3" => "15", "4" => "16", "5" => "18", "6" => "20", "7" => "22", "8" => "24", "9" => "26", "10" => "28", "11" => "30", "12" => "32", "13" => "34"), '5' => array("1" => "36", "2" => "38", "3" => "40", "4" => "42", "5" => "44", "6" => "46", "7" => "48", "8" => "50", "9" => "52", "10" => "54", "11" => "56", "12" => "58", "13" => "60"), '6' => array("1" => "70", "2" => "80", "3" => "90", "4" => "100", "5" => "110", "6" => "120", "7" => "130", "8" => "140", "9" => "150", "10" => "160", "11" => "170"));
/**TODO externalLabTesting is pending so i commented**/
$ArrOrderEntryPages = array(
    '',
    'entry',
    'secondtbl',
    'thirdTbl',
    'newFourthTbl',
    'cuttingRatioTbl',
    'deliverySchedule',
    //'fifthTbl',
    //'sixthTbl', is now cuttingRatioTbl
    /*'beforeSeventhtbl',
    'seventhtbl',
    'fabDetailKnitColorWiseFabBlendAndContent',
    'eighthtbl',
    'dyeingninthtbl',*/
    'emblishmenttenthtbl',
    //'bom_article1',
    /*'oe_BomCons12_article1',*/
    //'bom1_consolidated',
    //'bomSourcingDetailsArticle1',
    //'bomSamplingAndApprovalDetailsArticle1',
    //'bom_sampling13Approval_article1',
    //'bomdetailseleventhtblartcltwo',
    /*'bomconsolidatedtwelfthartcltwo',*/
    //'bom2_consolidated',
    //'bomSourcingDetailsArticle2',
    //'bomSamplingAndApprovalDetailsArticle2',
    //'bomsssapprovalthirteenthart2',
    'comgarmentprocessflow',
    'garmentsamplingfifteen',
    //'labtestingsixteen',
    //'externalLabTesting',
    'packingdetails',
    'cartonAndBags',
    //'masterbagassorteighteen',
    //'cartonbagassortnineteen',
    'lotinspectiontwentyone',
    'docandlogisticstwentytwo'
);

define("ARR_BOM_PAGES", array(1 => "article_1", 2 => "consolidated_1", 3 => "sourcingDetailsArticle_1", 4 => "samplingAndApprovalDetails_1",
    5 => "article_2", 6 => "consolidated_2", 7 => "sourcingDetailsArticle_2", 8 => "samplingAndApprovalDetails_2"));
define('ARRORDERENTRYPAGES', serialize($ArrOrderEntryPages));

define("ARR_FABRIC_PROGRAM_PAGE_NO", array("", "home", "two", "three", "dyeingColDetails", "partsXsQty", "pcsWeight",  "fabCcProcessLoss",
    "fabConCalc", "diaOrDim", "fabRequirement", "yarnDyeingStrips", "singleDyeingBath", "yarnDyeingJacquard", "thirteen", "fabKnittingProgram", "labTesting",
    "extLabTesting"));

/*The key must be from the above array (tableid for DB table)*/
/*
 * "fabRequirement"=>"fR" stored only combo##component##color##parts = planFabWeightSubtotal
*/
$ArrFabricProgramPages = array("home" => array("parts", "yarnBC"), "two" => "feederLycra", "three" => "fF", "dyeingColDetails" => "dyeD",
    "partsXsQty" => "xsQty", "pcsWeight" => "pW", "fabCcProcessLoss" => "fCcPL", "fabConCalc" => array("cummuConCalc"), "diaOrDim" => "dDimension",
    "fabRequirement"=>"fR", "yarnDyeingStrips" => "yds", "singleDyeingBath" => "sdb", "yarnDyeingJacquard" => "ydj",
    "eleven" => "11", "thirteen" => array("13", "13A"), "fabKnittingProgram" => "14", "labTesting" => "15", "extLabTesting" => "16");
define("ARR_FABRIC_PROGRAM_PAGES", serialize($ArrFabricProgramPages));
define('LIMITPERPAGE', 5);
define('PRICEQUOTEDFOR',array(1=>'CIF',2=>'C & F',3=>'FOB'));
$ArrBagsType = array(1=> "Solid Colour Solid Size - MPB & CAR",2=> "Solid Color Solid Size - CAR",3=> "Solid Colour Assorted Size - MPB & CAR",
4=> "Solid Color Assorted Size - CAR",5=> "Assorted Colour Solid Size - MPB & CAR",6=> "Assorted Colour Solid Size - CAR",
7=> "Assorted Colour Assorted Size - MPB & CAR",8=> "Assorted Color Assorted Size - CAR");
define("BAGS_TYPE",$ArrBagsType);

// Default Date and Time 
define("LOGTIME", date('Y-m-d H:i:s'));
//// newly included ///
define('ARRSUBSCCATEGORY', serialize(array('1' => 'Knit', '2' => 'Woven', '3' => 'Hybrid'))); 
define('ARRPURCHASETYPE', serialize(array('1' => 'New Purchase', '2' => 'Renewal', '3' => 'Package Migration','4' => 'Additional User','5'=>'Add. Data Storage','6'=>'Add. File Storage'))); 
define('ARRPURCHASETYPEENQUIRY', serialize(array('1' => 'New Purchase', '4' => 'Additional User','5'=>'Add. Data Storage','6'=>'Add. File Storage'))); 
define('ARRDATASTORAGE', serialize(array('0' => 'Nil', '2' => '2GB', '4' => '4GB','8' => '8GB','16' => '16GB','32' => '32GB','64' => '64GB','128' => '128GB','256' => '256GB','512' => '512GB','1024' => '1024GB'))); 
define('ARRFILESTORAGE', serialize(array('0' => 'Nil', '2' => '2GB', '4' => '4GB','8' => '8GB','16' => '16GB','32' => '32GB','64' => '64GB','128' => '128GB','256' => '256GB','512' => '512GB','1024' => '1024GB'))); 
define('ARRBUSINESSTYPE', serialize(array('1' => 'Proprietorship', '2' => 'Partnership', '3' => 'Pvt.Ltd.','4' => 'Ltd.','5' => 'Others')));
define('ARRREQUESTSTATUS', serialize(array('0' => '-', '1' => 'PENDING', '2' => 'PROFORMA REQUEST RAISED')));
define('ARRSUBSCRIPTIONPERIOD', serialize(array('1' => 'Quarterly', '2' => 'Half Yearly', '3' => 'Annually'))); 
define('ARRPAYMENTMODE', serialize(array('1' => 'IMPS', '2' => 'NEFT', '3' => 'RTGS','4' => 'SWIFT','5' => 'CASH','6' => 'CHEQUE'))); 
define('ARRPAYMENTSTATUS', serialize(array('1' => 'PENDING', '2' => 'CONFIRMED','3'=>'CANCELLED')));
define('ARRENABLE', serialize(array('1' => 'Enable', '2' => "Disable")));