<?php
/*
* The MIT License (MIT)
* 
* Copyright (c) 2013 Iztok Svetik
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* -----------------------------------------------------------------------------
* @author   Iztok Svetik
* @website  http://www.isd.si
* @github   https://github.com/iztoksvetik
*/

include_once(dirname(__FILE__) . '/../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../init.php');

if (isset($_POST)) {
	extract($_POST);
}
else {
	exit;
}
$url = Configuration::get('SENDYNEWSLETTER_INSTALLATION') . '/subscribe';
$ip_set = (int)Configuration::get('SENDYNEWSLETTER_IP');
$ip_var = Configuration::get('SENDYNEWSLETTER_IPVALUE');
$list = Configuration::get('SENDYNEWSLETTER_LIST');
$name_input = Configuration::get('SENDYNEWSLETTER_NAME');

$data = array(
	'list'		=> $list,
	'email' 	=> $email,
	'boolean'	=> 'true'
);

if ($name_input) {
	$data['name'] = $name;
}

if ($ip_set == 1 && $ip_var && !empty($ip_var)) {
	$data[$ip_var] = $ip;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_exec($ch);