<?PHP

/*
 File: save.php
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

session_start() ;
$base="contributors";
$docContent = $_POST['data'] ;
$docData = $_POST['document'] ;
$statData = json_decode($_POST['stats']);
$user =json_decode(base64_decode($_COOKIE['user']),true);
$saveType=$_POST['type'];
$versions=$_POST['versioni'];








	$it=getNumberFolder($base);//da qui in avanti creo le cartelle se non esitono
	$opera  = preg_split('/\\//',$docData['path']) ;
	$returnopera=$opera;

	$end1=end($opera);
	$end1= preg_split('/\./',$end1)[1] ;




		$mySchool=@array_search_partial(scandir("../".$base), $user["school"]);
if($end1=="txt"){
		if(!$mySchool){
				mkdir("../".$base."/".$it." - ".$user["school"], 0777, true);
		}
		$mySchool=array_search_partial(scandir("../".$base), $user["school"]);
		$testo=@array_search_partial(scandir("../".$base."/".$mySchool),$opera[1]);
		if(!$testo){
				mkdir("../".$base."/".$mySchool."/".$opera[1], 0777, true);

				//$opera3=@array_search_partial(scandir("../".$base."/".$mySchool."/".$opera[1]."/00 - Metadata"),"/00 - Metadata");
				//f(!$opera3){
				mkdir("../".$base."/".$mySchool."/".$opera[1]."/00 - Metadata", 0777, true);
				//}
				$fp1 = fopen("../".$base."/".$mySchool."/".$opera[1]."/00 - Metadata"."/"."infoDoc.json", "w");
				$fp1i=@json_decode(file_get_contents($fileDoc),true);
				if (is_null($fp1i)){
					$ok=fwrite($fp1, "[]");
				}
				fclose($fp1);
				$fp = fopen("../".$base."/".$mySchool."/".$opera[1]."/00 - Metadata"."/"."stats.json", 'w');
				$fpi=@json_decode(file_get_contents($fileDoc),true);
				if (is_null($fpi)){
					$ok=fwrite($fp, "[]");
				}
				fclose($fp);
		}
		$volume=@array_search_partial(scandir("../".$base."/".$mySchool."/".$opera[1]),$opera[2]);
		if(!$volume){
			   // mkdir("../".$base."/".$it." - ".$user["school"], 0777, true);
				mkdir("../".$base."/".$mySchool."/".$opera[1]."/".$opera[2], 0777, true);
		}




}
	$pp1=$_POST['path'];
		$file  = preg_split('/\\//',$_POST['path']) ;//cambio il path di dove prendere i json aggiungendo la scuola (pp1":"..\/sources\/01 - I promessi sposi","pp2":"..\/contributors\/01 - scuola di ingegneria\/01 - I promessi sposi")
		$file[1]=$base."/".$mySchool;
		$fileDoc1 = join('/',$file) ;
		$_POST['path']= join('/',$file) ;
	$pp2=$_POST['path'];

$fileDoc=$_POST['path']."/00 - Metadata/infoDoc.json";


$infoDoc=json_decode(file_get_contents($fileDoc),true);

$statfile=$_POST['path']."/00 - Metadata/stats.json";
$maxId=count($infoDoc)+1;


$returnPath=null;

$returnPath2=null;
function array_search_partial($arr, $keyword) {//cerca una stringa parziale in un array e ritorna l'elemento dell'array
    foreach($arr as $index => $string) {
    	$string1= strtolower($string);
    	$keyword1= strtolower($keyword);
        if (strpos($string1, $keyword1) !== FALSE)
            return $string;
    }
}
function getNumberFolder($base){//ritorna il successivo numero da dare alla scuola in caso di creazione della suddetta
		$it=0;
		$iterator = new DirectoryIterator("../".$base);
		foreach ( $iterator as $item ) {
		$filename = $item->getFileName() ;
		if( substr( $filename ,0,1 ) != '.') {
			if(substr($filename,0,2)!== "00"){

				if ($item->isDir() ) {
					$it=$it+1;
				}

			}
		}
	}
	$it=$it+1;//conta da 0, voglio che conti da 1
	return str_pad($it, 2, '0', STR_PAD_LEFT);//aggiunge lo 0 prima dei numeri da 1 a 9
}

if($saveType == "save"){

	$newPath = preg_split('/\//',$docData['path']) ;
	$id=explode('.',$newPath[4]);
	array_pop($newPath);
	$newPath[]=$id[0];
	if (isset ($docData['authors'])&& in_array($user['name'],$docData['authors'])) {
		foreach($infoDoc as $key=>$val){
			if($val['id'] == strval($id[0])){
				/*$result["sas"]="Salva";
				$result['sas1']=$val['id'];
				$result['sas2']=$id[0];
				$result['sas3']=$key;
				$result['sas4']=$val;
				$result["nonmeloposso"]=$infoDoc;
				echo json_encode($result);*/
				$infoDoc[$key]["tei"]=$docData['tei'];
			}
			/*else{
				$result['sas']="nosalva";
				$result['sas1']=$val['id'];
				$result['sas2']=$id[0];
				$result['sas3']=$key;
				$result['sas4']=$val;
				echo json_encode($result);
			}*/
		}
		file_put_contents($fileDoc, json_encode($infoDoc,JSON_PRETTY_PRINT));
		$ok=file_put_contents("../".$docData['path'],$docContent);
		$result["success"]=true;
		$result["reason"]="File salvato correttamente";
			//$result["oggettidadebuggare15"]=$newPath;
		echo json_encode($result);
	}
	else {
		http_response_code(401);
		$result["success"] = false;
		$result["reason"] = "La tua attuale autenticazione non consente questa operazione";
		echo json_encode($result);
		exit;
	}
}
else {//salva come nuovo
	if (isset($user['name']) ) {
		$name = $user['name'] ;
	} else {
		$name = 'unknown' ;
	}
	//$pathParts = preg_split('/\//',$docData['path']) ;

	$file  = preg_split('/\\//',$docData['path']) ;//cambio il path di destinazione
	$end=end($file);
	$end= preg_split('/\./',$end)[1] ;
	$returnPath2=$end;
	if($end=="txt"){
		$file[0]=$base."/".$mySchool;
		$fileDoc1 = join('/',$file) ;
		$docData['path']= join('/',$file) ;
	}


	$oldPath=$docData['path'];
	$newPath = preg_split('/\//',$oldPath) ;
	array_pop($newPath);
	$newPath[]=$maxId;
	$newPathStr=join($newPath,"/");
	$newPathStr=$newPathStr.".html";
	$returnPath=$newPathStr;
	$myfile = fopen("../".$newPathStr, "w");
	$ok=fwrite($myfile, $docContent);
	fclose($myfile);
	$newEl=array("id"=>strval($maxId),
		//"order"=>$docData['name'],
		//"label"=>$docData['label'],
		"authors"=>array($user['name']),
		//"path"=>$newPathStr,
		"versions"=>array($versions['old'],$versions['new']),
		"tei"=>$docData['tei']);
	$infoDoc[]=$newEl;
	file_put_contents($fileDoc, json_encode($infoDoc,JSON_PRETTY_PRINT));
}



if ($ok) {
	$stats = json_decode(file_get_contents($statfile),true );
	$x = &$stats ;
	for ($i = 2; $i< count($newPath); $i++) {
		if (!isset($x[$newPath[$i]])) {
			$x[$newPath[$i]] = array() ;
		}
		if (($i+1)==count($newPath))
			$x[$newPath[$i]]=($statData);
		$x = &$x[$newPath[$i]] ;
	}
	$ok = file_put_contents($statfile,json_encode($stats,JSON_PRETTY_PRINT))  ;
	$result["success"] = true;
	$result["reason"] = "Nuovo file salvato correttamente";
	$result["path"]=$returnPath;
	$result["path2"]=$returnPath2;
	$result["opera"]=$returnopera;
	$result["end"]=$end1;
	$result["pp1"]=$pp1;$result["pp2"]=$pp2;
	/*$result["oggettidadebuggare"]=@$_POST['path'];
	$result["oggettidadebuggare1"]=@$file;
	$result["oggettidadebuggare2"]=@$_POST['path']."/00 - Metadata/infoDoc.json";
	$result["oggettidadebuggare3"]=@$fileDoc;
	$result["oggettidadebuggare4"]=@$fileDoc1;
	$result["oggettidadebuggare5"]=@$mySchool;
	$result["oggettidadebuggare6"]=@$user['name'];
	$result["oggettidadebuggare7"]=@$oldPath;
	$result["oggettidadebuggare8"]=@$newPathStr;
	$result["oggettidadebuggare9"]=@$saveType;*/
}
else {
	http_response_code(403);
	$result["success"] = false;
	$result["reason"] = "Non ho potuto scrivere il file";
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
function p($s) {
	echo ('<xmp>') ;
	print_r($s) ;
	echo ('</xmp>') ;
}

echo json_encode($result);
die();

?>
