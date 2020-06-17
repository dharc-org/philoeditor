<?PHP

/*
 File: getFiles.php
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

function updateInfodoc($dir)
{
	foreach ($dir as $item)
	{
		 if(substr($item->getFileName(),0,2)=== "00"){
		   return $item->getPath()."/".$item->getFileName()."/infoDoc.json";
		}
	}
}
function deepDir($dir = null, $func = null, $ignore=0,$infoDoc) {
	//////p("\n");////p("\n");////p("\n");////p("\n");////p("\n");////p("\n");
	$id=0;
	if ($func === null) { //all'inizio non lo è
	////p("aaaaaaa");
		$func = function($info) {
			return $info->getFileName() ;
		} ;
	}

	$files = array() ;
	$iterator = new DirectoryIterator($dir);
	if (updateInfodoc($iterator))
		$infoDoc=updateInfodoc($iterator);
		////p("1");
		////p($infoDoc);
		////p("\n");
	foreach ( $iterator as $item ) {
		$filename = $item->getFileName() ;
		////p("2");
		////p($filename);// c'è .  ..  01 - I promessi sposi
		////p("\n");
		if( substr( $filename ,0,1 ) != '.') {
			if(substr($filename,0,2)!== "00"){
				$roba = $func($item, $item->isDir(), $ignore,$infoDoc) ;
				////p("3");
				//p($roba);

				if ($item->isDir() ) {
					//p("4");
					////p($dir.DIRECTORY_SEPARATOR.$filename);
					////p("\n");
					$content = deepDir($dir.DIRECTORY_SEPARATOR.$filename, $func, $ignore,$infoDoc) ;
					//p($content);
					//p("5");
					//p($dir.DIRECTORY_SEPARATOR.$filename);
					$roba['content'] = $content ;
					//p("content");
					//p($content);
				}
				if (!is_null($roba)){
						//////p("sassss");
						$files[] = $roba ;
					}

			}
		}
	}
	return $files;
}


function searchForId($id, $array,$id1) {
   foreach ($array as $key => $val) {
 //  	p($key);
 //  	p($val);
       if ($val[$id1] == $id) {
           return $key;
       }
   }
   return null;
}/*
function array_search_partial($arr, $keyword) {
    foreach($arr as $index => $string) {
    	$string1= strtolower($string);
    	$keyword1= strtolower($keyword);
        if (strpos($string1, $keyword1) !== FALSE)
            return $string;
    }
}*/
$convert = function($info, $isDir = false, $ignore=0,$infoDoc) {
	//p($info->getFileName());
	global $counter;
	$file  = preg_split('/\./',$info->getFileName()) ;
	//p($file);
	$parts = preg_split('/\s*-\s*/',$file[0]) ;
	//p("6");
	//p($parts);
	$path  = $info->getPathName() ;
/*
$mySchool=array_search_partial(scandir("../files1"), "papera");
p($mySchool);
if(!$mySchool){
	p("nein");
}*/
//	p("sa");
//	p($infoDoc);
//	p("\n");
	$path=preg_replace("{\\\}",'/', $path);
	$tolabel=$path;//copia di $path
	//p("pathprima");
	//p($path);
	$pathParts = preg_split('/\//',$path) ;
	//p($pathParts);
	$pathParts = array_slice( $pathParts, $ignore, NULL, true) ;
	//p($pathParts);
	////p("convert0");
	$path = join('/',$pathParts) ;
	//p("pathdopo");
	//p($path);
	//p($parts);
	if ($isDir) {
		////p("convert1");
		$ret = array(
			'order' => @$parts[0],
			'label' => @$parts[1],
			'path'  => @$path
		) ;
	} else {
		////p("convert2");
		////p($infoDoc);
		$doc=json_decode(file_get_contents($infoDoc),true);
//		p("json");
		//p($doc);
		//////p($doc);
//		p($file[0]);
		$sear=searchForId($file[0],$doc,"id");//cerco la posizione all'interno del json del nome del  file html
		//p("sear");
		//p($doc[$sear]);
		//$identificativo=json_decode($doc[0]['id']);
		//p($identificativo);
		$ret=@$doc[$sear];

		//p($file);p($file[0]);
		//p($file[0]-1);

		//p($ret);
		if (!is_null($ret)){
			//$path1=preg_replace("{\\\}",'/', $path);
			//p("path1");
			//p($path1);
			//p($info->getPathName());

			$ret['path'] = @$path;
			$ret['version'] =@leggiVersion($path);
			$ret['order'] =@leggiVersion($path);//null se html
			if(!$ret['order']==""){//html è ""
				if (intval($ret['order'])==0){//per pinocchio
					global $counter;
					//p($counter);
					$ret['order']=$counter;
				}
			}
			//$ret['id'] =@$counter;
			setCounter();
			//$ret['label'] =@leggiLabel($path);/////////////////////////
			$ret['label'] =@getLabel($tolabel);
			//p($ret);

//path":"files1/01 - I promessi sposi/Group1/01 - Tomo 1/1.txt","version":"1827"}
//path":"files1\01 - IBB\Group1\01 - Tomo 1\1.txt","version":"1827"
//			p($ret);
		}else{
//			;p("c'è un file in più nelle sources che non c'è nel json");
		}

		////p("convert3");


		////p(gettype($ret));
		////p("\n");////p("\n");////p("\n");////p("\n");
	}
	return $ret ;
} ;
function setCounter(){
	global $counter;
	$counter=$counter+1;
}
function getLabel($info){

				$lab = preg_split('/\s*-\s*/',$info) ;
				//p(sizeof($lab));
			  $ret=$lab[sizeof($lab)-1];
			  //$lab1 = preg_split('/\s*\\\\s*/',$ret) ;
			  $lab1 = preg_split('/\s*\/\s*/',$ret) ;
			  $return=$lab1[0];

			  return $return;
}
function p($s) {
	echo ('<xmp>') ;
	print_r($s) ;
	echo ('</xmp>') ;
}
function leggiVersion($myf){
//	p("\n");p("\n");p("\n");
	$path=__DIR__ . '/../';
	$path.=$myf;
//	p("8");
//	p($path);
	$fn = fopen($path,"r");
	$result = fgets($fn);
	/*if(strpos($result, '#') !== false){
		p("sto cazzo");}
	p($result);

	$result = fgets($fn);
	if(strpos($result, '#') !== false){
	p("sto cazzo");}
	p($result);

	$result = fgets($fn);
	if(strpos($result, '#') !== false){
		p("sto cazzo");}
	p($result);*/
//	p("9");
	//p($result);
	$return=NULL;
	$count=0;
	while(contains($result,"#") == false && contains($result,"<h1>") == false && $count<10)  {
		$result = fgets($fn);
		//p($result);
		if(contains($result,"<h1>")){
			$return=NULL;
			//p("h1");
		}else if (contains($result,"#")) {//tolgo # all'inizio
			 $lab = preg_split('/\s*#\s*/',$result) ;
			// p($lab);
			 $return=$lab[sizeof($lab)-1];

			 $lab = preg_split('/\s*\n\s*/',$return) ;//tolgo lo spazio alla fine
			  $return=$lab[0];

			 //p("###");
		}
		//p($count);
		$count=$count+1;

	  }

	fclose($fn);

//	p($return);
//	p("fine");
	return $return;
}
function leggiOrder($myf){
//	p("\n");p("\n");p("\n");
	$path=__DIR__ . '/../';
	$path.=$myf;
//	p("8");
//	p($path);
	$fn = fopen($path,"r");
	$result = fgets($fn);
	/*if(strpos($result, '#') !== false){
		p("sto cazzo");}
	p($result);

	$result = fgets($fn);
	if(strpos($result, '#') !== false){
	p("sto cazzo");}
	p($result);

	$result = fgets($fn);
	if(strpos($result, '#') !== false){
		p("sto cazzo");}
	p($result);*/
//	p("9");
	//p($result);
	$return=NULL;
	$count=0;
	while(contains($result,"#") == false && contains($result,"<h1>") == false && $count<10)  {
		$result = fgets($fn);
		//p($result);
		if(contains($result,"<h1>")){
			$return=NULL;
			//p("h1");
		}else if (contains($result,"#")) {//tolgo # all'inizio
			 $lab = preg_split('/\s*#\s*/',$result) ;
			// p($lab);
			 $return=$lab[sizeof($lab)-1];

			 $lab = preg_split('/\s*\n\s*/',$return) ;//tolgo lo spazio alla fine
			  $return=$lab[0];

			 //p("###");
		}
		//p($count);
		$count=$count+1;

	  }

	fclose($fn);

//	p($return);
//	p("fine");
	if (intval($return)==0){
		global $counter;
		//p($counter);
		$return=$counter;
	}
	return intval($return);
}
function leggiLabel($myf){
//	p("\n");p("\n");p("\n");
	$path=__DIR__ . '/../';
	$path.=$myf;
//	p("8");
//	p($path);
	$fn = fopen($path,"r");
	$result = fgets($fn);
	/*if(strpos($result, '#') !== false){
		p("sto cazzo");}
	p($result);

	$result = fgets($fn);
	if(strpos($result, '#') !== false){
	p("sto cazzo");}
	p($result);

	$result = fgets($fn);
	if(strpos($result, '#') !== false){
		p("sto cazzo");}
	p($result);*/
//	p("9");
	//p($result);
	$return=NULL;
	$count=0;
	while(preg_match("/[a-z]/i", $result)==false  &&$count<10)  {

		$result = fgets($fn);
		if(!preg_match("/^[^<>]+$/", $result)){
			$result = fgets($fn);
			$lab = preg_split('/\s*<\s*/',$result) ;//tolgo </h1> dai file html
			  $return=$lab[0];
			  //p($return);

		}
		else if(preg_match("/[a-z]/i", $result)){
			//p($result);
   			 //p( "it has alphabet!");
			// $lab = @preg_split('/\s*#\s*/',$result) ;
			// p($lab);
			 //$return=@$lab[1];
			 $lab = preg_split('/\s*\n\s*/',$result) ;//tolgo lo spazio alla fine
			  $return=$lab[0];
			 // $return=$result;
			  //p($return);
			 //
		}
		//p($count);
		$count=$count+1;
		//p($return);

	  }

	fclose($fn);

//	p($return);
//	p("fine");
	return $return;
}

function contains($string,$s){
	if(strpos($string, $s) !== false){
		return true;}
	else{
		return false;
	}
}

function startsWith ($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}
$counter=1;
$dir = '../contributors' ;
$dirContent = deepDir($dir, $convert,1,"") ;
//p($dirContent);
$return = stripslashes(json_encode($dirContent)) ;
echo $return ;

?>
