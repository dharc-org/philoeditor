<?php

/*
 File: getOpera.php
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

error_reporting(E_ALL); ini_set('display_errors', 1);
$dir="../sources";//pjreddie.com

$iterator=new DirectoryIterator($dir);
$operas=array();
foreach ($iterator as $opera){
        //p($opera);
	if( substr( $opera->getFileName() ,0,1 ) != '.'){


		$metadir=@$opera->getPath()."/".$opera->getFileName()."/00 - Metadata";
		$style=@json_decode(file_get_contents($metadir."/style.json"));
        $header=@getHeader($opera);
        //p($header);
		//$header=@json_decode(file_get_contents($metadir."/header.json"));
        //p($header);
		$o=@array("header"=>$header,"style"=>$style,"path"=>$opera->getPath()."/".$opera->getFileName());
        //p($opera->getPath()."/".$opera->getFileName());//../files
		$operas[]=@$o;
	}
}

function p($s) {
    echo ('<xmp>') ;
    print_r($s) ;
    echo ('</xmp>') ;
}
function getHeader($opera){
   // p($opera->getFileName());
    $label  = preg_split('/\./',$opera->getFileName()) ;
    $label2 = preg_split('/\s*-\s*/',$label[0]) ;
    $header=$label2[1];
    //p($header);
    //$header=str_replace($header, "c",$header, $header);
    $hh ='{"title":"';
    $hh.=$header;
    $hh.='"}';
    //p($hh);
    $hh=(object) array('title' => $header);
    return $hh;//{"title":"IBB"}
}


echo json_encode($operas);
?>
