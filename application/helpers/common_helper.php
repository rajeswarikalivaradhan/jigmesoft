<?php defined('BASEPATH') OR exit('No direct script access allowed');
function fnGetUserLoggedInfo($allData = '')
{
    $ObjCI =& get_instance();
    $ArrProfileInfo = $ObjCI->session->userdata('UI');
    if (!empty($ArrProfileInfo['id'])) {
        if ($ArrProfileInfo['id'] >= 1) {
            if ($allData == 1) {
                return $ArrProfileInfo;
            } else if ($allData == 2) {
                return $ArrProfileInfo['companyid'];
            } else {
                return $ArrProfileInfo['id'];
            }
        } else {
            return '';
        }
    }
}

function fnIfCheckUserLoggedIn()
{
    $VarUserId = fnGetUserLoggedInfo();
    if (empty($VarUserId)) {
        redirect(base_url());
    }
}

function fnIfCheckUserLoggedInOld($usertype)
{
    //echo $usertype; die;
    if ($usertype == 3) {
        $VarBaseUrl = base_url('member');
    } elseif ($usertype == 2) {
        $VarBaseUrl = base_url();
    } else {
        $VarBaseUrl = 'http://app.garmenplus.com';
    }
    $VarUserId = fnGetUserLoggedInfo();
    if (empty($VarUserId)) {
        redirect($VarBaseUrl);
    }
//	if ($VarUserId=='') {
//		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//			echo json_encode(array('errcode'=>'404','rdl'=>'admin'));die;
//		} else {
//
//			redirect($VarBaseUrl);
//		}
//	}

}

//function fnIfCheckUserLoggedIn() {
//    $VarUserId					= fnGetUserLoggedInfo(1);
//    echo '<pre>'; print_r($VarUserId); die;
//    var_dump($VarUserId);
//    echo is_array($VarUserId);
//    die('df');
//
//    if(empty($VarUserId)) {
//        //redirect(base_url());
//    }
//    elseif ($VarUserId['id'] > 0) {
//        if($VarUserId['usertype'] == 1) {
//            redirect(base_url('cadmin/dashboard'));
//        }
//        elseif ($VarUserId['usertype'] == 2) {
//            redirect(base_url('company/dashboard'));
//        }
//
//    }
//}

function SendSMS($VarMobileNo = '', $VarMessage = '')
{
    $ObjCI = &get_instance();
    $VarUserName = $ObjCI->config->item('SMSUSERNAME');
    $VarPassword = $ObjCI->config->item('SMSPASSWORD');
    $VarSMSGatewayURL = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
    $VarMessage = urlencode($VarMessage);
    $ObjSMSCURL = curl_init();
    curl_setopt($ObjSMSCURL, CURLOPT_URL, $VarSMSGatewayURL);
    curl_setopt($ObjSMSCURL, CURLOPT_POST, 1);
    curl_setopt($ObjSMSCURL, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ObjSMSCURL, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ObjSMSCURL, CURLOPT_POSTFIELDS, "User=$VarUserName&passwd=$VarPassword&mobilenumber=$VarMobileNo&message=$VarMessage&sid=smscntry&mtype=N&DR=N");
    curl_setopt($ObjSMSCURL, CURLOPT_RETURNTRANSFER, 1);
    $ObjRes = curl_exec($ObjSMSCURL);
    $info = curl_getinfo($ObjSMSCURL);
    curl_close($ObjSMSCURL);
    if (empty($ObjRes)) {
        return false;
    } else {
        return true;
    }
}

function SendEmail($VarToEmailID = 'webneovignesh@gmail.com', $VarSubject, $VarMailerInfo, $ArrReplace = array(), $VarTestEmail = '')
{
    if ($VarMailerInfo <> "") {
        $VarEmailerTempFileName = str_replace("\\", "/", dirname(dirname(dirname(__FILE__)))) . "/application/views/emailtemplate/" . $VarMailerInfo . ".html";
        $ObjCI = &get_instance();
        if ($VarMailTemplate = fopen($VarEmailerTempFileName, "r")) {
            $VarContents = '';
            $VarContents = fread($VarMailTemplate, filesize($VarEmailerTempFileName));
            fclose($VarMailTemplate);
            if (!empty($ArrReplace)) {
                foreach ($ArrReplace as $VarSearchString => $VarReplaceString) {
                    $VarContents = str_replace($VarSearchString, $VarReplaceString, $VarContents);
                }
            }
            //echo "Tests";
            //$VarContents = getNotificationTheme($VarSubject, $VarContents, '');
            //$VarToEmailID = 'webneovignesh@gmail.com';
            /*$ObjCI->email->from('contact@garmenplus.com', 'GarmenPlus');
            $ObjCI->email->to($VarToEmailID);
            $ObjCI->email->subject($VarSubject);
            $ObjCI->email->message($VarContents);
            $ObjCI->email->set_mailtype('html');*/


            // To send HTML mail, the Content-type header must be set
            $ArrAddHeaders[] = 'MIME-Version: 1.0';
            $ArrAddHeaders[] = 'Content-type: text/html; charset=iso-8859-1';

            // Additional headers
            /*$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
            $headers[] = 'From: Birthday Reminder <birthday@example.com>';
            $headers[] = 'Cc: birthdayarchive@example.com';
            $headers[] = 'Bcc: birthdaycheck@example.com';*/
            if (mail($VarToEmailID, $VarSubject, $VarContents,implode("\r\n", $ArrAddHeaders))) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function str_rand($length = 8, $seeds = 'alphanum')
{
    // Possible seeds
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
    $seedings['hexidec'] = '0123456789abcdef';

    // Choose seed
    if (isset($seedings[$seeds])) {
        $seeds = $seedings[$seeds];
    }

    // Seed generator
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float)$sec + ((float)$usec * 100000);
    mt_srand($seed);

    // Generate
    $str = '';
    $seeds_count = strlen($seeds);
    for ($i = 0; $length > $i; $i++) {
        $str .= $seeds[mt_rand(0, $seeds_count - 1)];
    }

    return strtoupper($str);
}

function fnCreateSlugURL($VarString = '')
{
    $VarSlugURL = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $VarString));
    return $VarSlugURL;
}

function encrypt_url($string)
{
    $key = "MAL_979805"; //key to encrypt and decrypts.
    $result = '';
    $test = "";
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $test[$char] = ord($char) + ord($keychar);
        $result .= $char;
    }
    return urlencode(base64_encode($result));
}

function decrypt_url($string)
{
    $key = "MAL_979805"; //key to encrypt and decrypts.
    $result = '';
    $string = base64_decode(urldecode($string));
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result .= $char;
    }
    return $result;
}

function fnTruncWords($phrase, $max_words)
{
    $phrase_array = explode(' ', $phrase);
    if (count($phrase_array) > $max_words && $max_words > 0)
        $phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . '...';
    return $phrase;
}


//*********** Padding Function *********************
function pkcs5_pad($plainText, $blockSize)
{
    $pad = $blockSize - (strlen($plainText) % $blockSize);
    return $plainText . str_repeat(chr($pad), $pad);
}

//********** Hexadecimal to Binary function for php 4.0 version ********
function hextobin($hexString)
{
    $length = strlen($hexString);
    $binString = "";
    $count = 0;
    while ($count < $length) {
        $subString = substr($hexString, $count, 2);
        $packedString = pack("H*", $subString);
        if ($count == 0) {
            $binString = $packedString;
        } else {
            $binString .= $packedString;
        }

        $count += 2;
    }
    return $binString;
}

function fnGenerateCategoryURL($VarCategoryUrl = '')
{
    return base_url() . $VarCategoryUrl;
}

function fnStateList()
{
    $ObjCI = &get_instance();
    $ObjCI->load->model('statemodel');
    $ArrStateList = $ObjCI->statemodel->fnStateList();
    $ArrFnlStateList = array();
    $i = 0;
    foreach ($ArrStateList as $ArrStateKey => $ArrStateDetails) {
        $ArrFnlStateList[$ArrStateDetails['id']] = $ArrStateDetails['statename'];
        $i = $i + 1;
    }
    return $ArrFnlStateList;
}

function trimwordslist($s, $wordlen)
{
    return preg_replace('/((\w+\W*){' . $wordlen . '}(\w+))(.*)/', '${1}', $s);
}

function fnConvertKeyPair($ArrSource)
{
    $i = 0;
    foreach ($ArrSource as $VarKey => $VarVal) {
        $ArrReturn[$i]['id'] = (string)$VarKey;
        $ArrReturn[$i]['name'] = $VarVal;
        $i = $i + 1;
    }
    return $ArrReturn;
}

function get_keys_for_duplicate_values($my_arr, $clean = false)
{
    if ($clean) {
        return array_unique($my_arr);
    }

    $dups = $new_arr = array();
    foreach ($my_arr as $key => $val) {
        if (!isset($new_arr[$val])) {
            $new_arr[$val] = $key;
        } else {
            if (isset($dups[$val])) {
                $dups[$val][] = $key;
            } else {
                //$dups[$val] = array($key);
                // Comment out the previous line, and uncomment the following line to
                // include the initial key in the dups array.
                $dups[$val] = array($new_arr[$val], $key);
            }
        }
    }
    return $dups;
}

function fnGetAllRequestQueueNo($VarRequestId = '')
{
    $ObjCI =& get_instance();
    return $ObjCI->db->select('queueno')->from(KN_ALLREQUEST)->where('id', $VarRequestId)->get()->row();
}

function dateTimeHelp($date, $mysqlDatetime = true)
{
    if (strtotime($date) === false)
        return null;
    elseif ($date == '0000-00-00 00:00:00')
        return null;
    else {
        if ($mysqlDatetime) {
            return date('Y-m-d H:i:s', strtotime($date));
        } else {
            return date('d-m-Y H:i:s', strtotime($date));
        }
    }
}

function dateHelp($date, $mysqlDate = true)
{
    if (false === strtotime($date) || $date == '0000-00-00')
        return null;
    else {
        if ($mysqlDate) {
            return date('Y-m-d', strtotime($date));
        } else {
            return date('d-m-Y', strtotime($date));
        }
    }
}

function validateDate($date, $format = 'd-m-Y')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

function gcd($a, $b)
{
    return $b ? gcd($b, $a % $b) : $a;
}
/*function convertToMysql($dateTime,$date) {
    if(!empty($date) && $date !== '0000-00-00') {
        return date('Y-m-d', strtotime($date));
    }
    if(!empty($dateTime) && $dateTime !== '0000-00-00 00:00:00') {
        return date('Y-m-d H:i:s', strtotime($dateTime));
    }
}*/

function getUserTypeId($VarUserType) {
    $ArrUserType = unserialize(ARRUSERTYPE);

    $VarId = array_search($VarUserType,$ArrUserType);
    return $VarId;
    //echo '<pre>'; print_r(array_search($VaruUserType,$ArrUserType)); die('die');
}