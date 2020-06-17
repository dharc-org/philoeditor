
<?php

/*

 File: changePwd.php
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
$users = json_decode(file_get_contents($file),true) ;

$response=array();

$pwd_old=$data->old;
$user_name=$data->nome;
$pwd_new=$data->new1;
$user = change_password($users, $user_name, $pwd_old,$pwd_new) ;
if($user){
	foreach($users as $key=>$value){
		if(in_array($user_name,$value)){
			unset($users[$key]);
		}
	}
	$users[]=$user;

	file_put_contents($file,json_encode($users,JSON_PRETTY_PRINT));
	$response['result']="ok";

}
else{
	$response['result']="false";
}

echo json_encode($response);

function change_password($u, $n, $p,$pn) {
	$users = search_key($u, 'name', $n) ;
	if (count($users) != 1) {
		return null ;
	} else if ($users[0]['pwd'] == $p ){
		$users[0]['pwd']=$pn;
		return $users[0] ;
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
