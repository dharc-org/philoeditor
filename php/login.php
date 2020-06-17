<?php

/*
 File: login.php
 Authors: Maria Luce Bottazzo, Primiano Arminio Cristino, Angelo Di Iorio, Giulia Donati, Fabio Vitali
 Last change on: 17/06/2020

 Copyright (c) 2020 by the authors, DH.ARC + Department of Computer Science, University of Bologna

 Permission to use, copy, modify, and/or distribute this software for any
 purpose with or without fee is hereby granted, provided that the above
 copyright notice and this permission notice appear in all copies.

 THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
 SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION
 OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
 CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

$file = '../data/hidden/users.json' ;
$data=json_decode(file_get_contents("php://input"));
$pwd=$data->pwd;
$username=$data->user;
$users = json_decode(@file_get_contents($file),true) ;
if (!$users)
{
	http_response_code(500);
	$res=array("result"=>"error");
	echo json_encode($res);
	exit();
}
$user = check_password($users, $username, $pwd) ;
if ($user !== null) {
	session_start() ;
	$_SESSION['user'] = $user ;
	setcookie("user", base64_encode (json_encode($user)), 0, '/');
	$result["result"] = "ok";
	$result['user'] = $user ;
} else {
	session_start() ;
	unset($_COOKIE['user']) ;
	http_response_code(401);
	setcookie("user", null,0, '/');
	$result["result"]="wrong_login";
}

echo json_encode($result);

function check_password($u, $n, $p) {
	$users = search_key($u, 'name', $n) ;
	if (count($users) != 1) {
		return null ;
	} else if ($users[0]['pwd'] == $p ){
		$return = $users[0] ;
		unset($return['pwd']) ;
		return $return ;
	} else {
		return null ;
	}
}

function search_key($array,$key,$value) {
	$return = array() ;
	foreach ($array as $rec) {
		if (isset($rec[$key]) && $rec[$key] == $value) {
			array_push($return, $rec) ;
		};
	}
	return $return ;
}
?>
