<?php

/*
 File: register.php
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

if (!$users){
	http_response_code(501);
	exit;
}
$response=array();


$user_name=$data->userName;
$user=check_user($users,$user_name);

if(!$user){
	$response['result']="user_exist";
}
else{
	$nome=$data->nome;
	$cognome=$data->cognome;
	$sesso=$data->gender;
	$pwd=$data->pwd1;
	$scuola=$data->scuola;
	$users[]=array("name" => $user_name, "pwd" => $pwd,"showAs"=>$nome." ".$cognome,"gender"=>$sesso, "school"=>$scuola);


	$ok=file_put_contents($file, json_encode($users,JSON_PRETTY_PRINT),true);
	if(!$ok){
		http_response_code(501);
		exit;
	}
	else
		$response["result"]="ok";

	/* Per attivare servizio di notifica registrazione: scommentare e sostituire il parametro e-mail della funzione mail con indirizzo email dell'Amministratore
	$msg="Nuova registrazione su philoeditor da parte di ".$nome." ".$cognome." "."con nome utente"." ".$user_name." .";
	mail("e-mail","Philoeditor:nuova registrazione",$msg);
	*/
}

	echo json_encode($response);


function check_user($users, $user_name) {
	$user_match = search_key($users, 'name', $user_name) ;
	if (count($user_match) != 1) {
		return 1;
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
