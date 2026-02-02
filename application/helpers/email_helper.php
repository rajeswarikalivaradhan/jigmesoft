<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * getNotificationTheme()
 * 
 * @param mixed $message
 * @param mixed $subject
 * @param string $filePath
 * @return
 */
function getNotificationTheme($subject, $message,$cms='') {
	$CI					= & get_instance();
	$baseURL			= $CI->config->item('base_url');
	$siteName			= $CI->config->item('siteName');

	$contactno			= $CI->config->item('emailcontactno');
	$contactaddress		= COMPANYADDRESS1;
	$contactcompany		= COMPANYNAME;
	$filePath			= str_replace("\\", "/",dirname(dirname(dirname(__FILE__))))."/application/views/notification.html";

	//Get HTML contents of theme file.
	$fileContents		= file_get_contents($filePath);

	//Search with this patterns
	$searchPatterns[0]	= '<<!--currentdate-->>';
	$searchPatterns[1]	= '<<!--contents-->>';
	$searchPatterns[2]	= '<<!--baseURL-->>';
	$searchPatterns[3]	= '<<!--siteName-->>';
	$searchPatterns[4]	= '<<!--contactno-->>';
	$searchPatterns[5]	= '<<!--contactaddress-->>';
	$searchPatterns[6]	= '<<!--companyname-->>';

	//Replace with this values
	$replaceBy[0]		= date('d-m-Y');
	$replaceBy[1]		= $message;
	$replaceBy[2]		= $baseURL;
	$replaceBy[3]		= $siteName;
	$replaceBy[4]		= $contactno;
	$replaceBy[5]		= $contactaddress;
	$replaceBy[6]		= $contactcompany;

	//Return the theme processed contents.
	return preg_replace($searchPatterns, $replaceBy, $fileContents);
}
