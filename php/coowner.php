<?php

/*
 File: coowner.php
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

$data=json_decode(file_get_contents("php://input"));
$fileDoc='../data/hidden/users.json';
$users = json_decode(file_get_contents($fileDoc),true) ;

$fileDoc=$data->path."/00 - Metadata/infoDoc.json";
$res=file_get_contents($fileDoc);
$infoDoc = json_decode($res,true) ;

$response=array();

$data=json_decode(file_get_contents("php://input"));
$user=$data->owner->name;
$doc=$data->document;
$newOwner=$data->coowner;
$id=$data->document->id;
$owner=check_owner($doc->authors,$user);
if($owner){
	$coowner=check_owner($doc->authors,$newOwner);
	if(!$coowner){

		$regUser=check_user($users,$newOwner);
		if(!$regUser){
			$addOwner=search_key($infoDoc,'id',$doc->id);
			foreach($infoDoc as $key=>$value){
				if($value['id'] == $id){
					$infoDoc[$key]['authors'][]=$newOwner;
				}
			}
			$c=file_put_contents($fileDoc, json_encode($infoDoc,JSON_PRETTY_PRINT));
			$response["result"]="ok";
		}
		else{
			$response["result"]="no_user";
		}
	}
	else{
		$response["result"]="already_owner";
	}
}
else
{
	$response["result"]="no_owner";
}

echo json_encode($response);

function check_owner($d,$u) {
	foreach ($d as $rec) {
		if($rec == $u){
			return 1;
		}
	}
	return 0;
}

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
