<?php

use Cake\Routing\Router;


$siteFolder = dirname(dirname($_SERVER['SCRIPT_NAME']));
$config['App.siteFolder'] = $siteFolder;
$config['Site.Title'] 		= 		'TatvaTest';
$config['AdminEmail'] 		= 		'TatvaTest@gmail.com';

if (isset($_SERVER['HTTPS'])) {
    if ($_SERVER['HTTPS'] == "on") {
        $config['SITEURL'] = 'https://' . $_SERVER['HTTP_HOST'] . $siteFolder . "/";
    }
} else {
    $config['SITEURL'] = 'http://' . $_SERVER['HTTP_HOST'] . $siteFolder . "/";
}


$config['SLIDERS.MAIN_ICON'] 			= 		WWW_ROOT . DS . 'uploads/sliders';
$config['GENDER_LIST'] 					= 		[1 => 'Male', 2 => 'Female'];
$config['ROLES'] 						= 		['Admin' => 1,'User' => 2];
$config['status'] 						= 		[1 => 'Active',0 => 'Inactive'];
$config['ADMIN_PAGE_LIMIT'] 			= 		10;
$config['Currency'] 					= 		['symbal' => __('$'), 'code' => __('US')];
$config['FrontPageLimit'] 				= 		1;
$config['languages'] 					= 		array('1' => __('English'), '2' => __('Spanish'));
$config['CommonActions'] 				= 		array('1' => __('Set Activated'), '2' => __('Set Deactivated'));

$config['USERS.MAIN_ICON'] 				= 		WWW_ROOT . DS . 'uploads/users';
$config['USERS.THUMB_ICON'] 			= 		WWW_ROOT . DS . 'uploads/users/thumb';
$config['USERS.THUMB_WIDTH']			= 		200;
$config['USERS.THUMB_HEIGHT'] 			= 		200;

$config['NFC_NUMBER_LENGTH'] 		    = 		8;
$config['HOWMANY_NFC_NUMBER_GENERATE']  = 		5;



$config['MODULE_ACCESS']	=	[
	'dashboard'			=>	'Dashboard',
	'users'				=>	'Users'
];



$config['STATE_LIST']	=	[
	'Andhra Pradesh'		=>	'Andhra Pradesh',
	'Arunachal Pradesh'		=>	'Arunachal Pradesh',
	'Assam'					=>	'Assam',
	'Bihar'					=>	'Bihar',
	'Chhattisgarh'			=>	'Chhattisgarh',
	'Chandigarh'			=>	'Chandigarh',
	'Dadra and Nagar Haveli'=>	'Dadra and Nagar Haveli',
	'Daman and Diu'			=>	'Daman and Diu',
	'Delhi'					=>	'Delhi',
	'Goa'					=>	'Goa',
	'Gujarat'				=>	'Gujarat',
	'Haryana'				=>	'Haryana',
	'Himachal Pradesh'		=>	'Himachal Pradesh',
	'Jammu and Kashmir'		=>	'Jammu and Kashmir',
	'Jharkhand'				=>	'Jharkhand',
	'Karnataka'				=>	'Karnataka',
	'Kerala'				=>	'Kerala',
	'Madhya Pradesh'		=>	'Madhya Pradesh',
	'Maharashtra'			=>	'Maharashtra',
	'Manipur'				=>	'Manipur',
	'Meghalaya'				=>	'Meghalaya',
	'Mizoram'				=>	'Mizoram',
	'Nagaland'				=>	'Nagaland',
	'Orissa'				=>	'Orissa',
	'Punjab'				=>	'Punjab',
	'Pondicherry'			=>	'Pondicherry',
	'Rajasthan'				=>	'Rajasthan',
	'Sikkim'				=>	'Sikkim',
	'Tamil Nadu'			=>	'Tamil Nadu',
	'Tripura'				=>	'Tripura',
	'Uttar Pradesh'			=>	'Uttar Pradesh',
	'Uttarakhand'			=>	'Uttarakhand',
	'West Bengal'			=>	'West Bengal'
];



