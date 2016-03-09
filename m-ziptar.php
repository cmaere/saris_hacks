<?php

//
//
// M-ZIP Library: unzipping files using command line tools
//
// Feel free to use, modify and distribute this but please keep this note intact
// (you may add at the bottom)
// Please if you make modifications, fix bugs, etc... let me know!
//
//
// Original author: Francesco M. Munafo'
//
// EMail: phpDev (AT) eSurfers (DOT) com
// Download latest at http://eSurfers.com/m-lib/
//
//


if(defined('M_ZIP_LIB')) return; // BLOCK DOUBLE INCLUDES
define('M_ZIP_LIB',true);





/////////// C O N F I G U R A T I O N ///////////

// You can override these in the including file if you wish



// FOLDER WHERE THE FILES CAN BE UNZIPPED:
//
if(!defined('UNZIP_DIR'))
	define('UNZIP_DIR','/home/content/z/a/l/zalongwa/html/muchs/install');
	//define('UNZIP_DIR','/path/to/unzip/directory/');


// COMMAND LINE TOOL FOR UNZIPPING:
// (program will automatically put source zip file in @_SRC_@ and destination directory in @_DST_@)
// (do not remove @_SRC_@ and @_DST_@ tags)
//
if(!defined('UNZIP_CMD'))
	define('UNZIP_CMD','unzip -o @_SRC_@ -x -d @_DST_@');
//
// note that destination directory will be a SUBFOLDER of UNZIP_DIR
// note that source zip file will be the argument you give to m_zip_open();



/////////// /////////// /////////// ///////////








// Example START
/*


$zip = m_zip_open("samplefile.zip");

if ($zip) {
	while ($zip_entry = m_zip_read($zip)) {
		echo "<br>Name:" . m_zip_entry_name($zip_entry) . "\n";
		echo "<br>Actual Filesize:" . m_zip_entry_filesize($zip_entry) . "\n";
		echo "<br>Compressed Size:" . m_zip_entry_compressedsize($zip_entry) . "\n";
		echo "<br>Compression Method:" . m_zip_entry_compressionmethod($zip_entry) . "\n";

		if (m_zip_entry_open($zip, $zip_entry, "r")) {
			echo "<p>File Contents:\n";
			$buf = m_zip_entry_read($zip_entry, m_zip_entry_filesize($zip_entry));
			echo "$buf\n<p><p>";

			m_zip_entry_close($zip_entry);
		}
		echo "\n";

	}
	m_zip_close($zip);

}




*/
// PHP Example END










function m_zip_open($zipFile) {
	global $m_zip_dirHandles,$m_zip_dirOpenPaths;

	// if(!empty($m_zip_open_file) && $m_zip_open_file!='') m_zip_close($m_zip_open_file);


	list ($usec, $sec) = explode (" ", microtime());
	$ID = ($sec - 1007700000) . str_pad ((int)($usec * 1000000), 6, "0", STR_PAD_LEFT) . str_pad (rand (0, 999), 3, "0", STR_PAD_LEFT);


	$m_zip_open_entry='';
	$m_zip_open_file=split('/',$zipFile);
	$m_zip_open_file='Z'.$ID.$m_zip_open_file[count($m_zip_open_file)-1];

	$zipDir=UNZIP_DIR.$m_zip_open_file.'/';

	mkdir($zipDir);

	$unzipCmd=UNZIP_CMD;
	$unzipCmd=str_replace('@_SRC_@',$zipFile,$unzipCmd);
	$unzipCmd=str_replace('@_DST_@',$zipDir,$unzipCmd);


	// UNIX ONLY
	//$res=rtrim(`$unzipCmd; echo $?`);

	$res=-1; // any nonzero value
	$UnusedStringResult=exec ($unzipCmd,$UnusedArrayResult,$res);
	// THIS TO TEST UNZIP AVAILABLE:
	// $UnusedStringResult=exec ('unzip',$UnusedArrayResult,$res);

	// UNCOMMENT FOR DEBUGGING:
	// echo("<pre>\n");
	// echo($unzipCmd."<p>\n");
	// echo(implode("<br>\n",$UnusedArrayResult)."<br>\n<br>\n");
	// echo("</pre>\n");

	// IT IS IMPORTANT THAT YOUR COMMANDLINE ZUNZIP TOOL CORRECTLY SETS RESULT CODE
	// result code 0 == NO ERROR as in:
	if($res!=0) {
		m_deldir($zipDir);
		return FALSE;
	}

	// OTHERWISE, you still have the option of parsing $UnusedArrayResult to find clues of errors
	// (lines starting with or "inflating" mean no error)

	$m_zip_open_dirs=array(opendir($zipDir));
	$m_zip_dir_paths=array($zipDir);

	$m_zip_dirHandles[$zipFile]=false;
	unset($m_zip_dirHandles[$zipFile]);
	$m_zip_dirOpenPaths[$zipFile]=false;
	unset($m_zip_dirOpenPaths[$zipFile]);

	return array($zipFile,$m_zip_open_file,$m_zip_open_dirs,$m_zip_dir_paths);
}




function m_zip_close($OpenZipFile) {
	global $m_zip_dirHandles,$m_zip_dirOpenPaths;

	// if(empty($m_zip_open_file) || $m_zip_open_file=='') return FALSE;

	if(!is_array($OpenZipFile)) {
		return;
	}

	$m_zip_original_file=$OpenZipFile[0];
	$m_zip_open_file=$OpenZipFile[1];
	$m_zip_open_dirs=$OpenZipFile[2];

	$zipDir=UNZIP_DIR.$m_zip_open_file.'/';


	$m_zip_dirHandles[$m_zip_original_file]=false;
	unset($m_zip_dirHandles[$m_zip_original_file]);
	$m_zip_dirOpenPaths[$m_zip_original_file]=false;
	unset($m_zip_dirOpenPaths[$m_zip_original_file]);

	if(is_dir($zipDir)) {
		foreach($m_zip_open_dirs as $opendir) closedir($opendir);
		m_deldir($zipDir);
	}
	$m_zip_open_file='';
	return TRUE;
}



function m_zip_read($OpenZipFile) {
	global $m_zip_dirHandles,$m_zip_dirOpenPaths;

	if(!is_array($OpenZipFile)) {
		return FALSE;
	}

	$m_zip_original_file=$OpenZipFile[0];
	$m_zip_open_file=$OpenZipFile[1];


	// LOAD FILEHANDLES AND PATHS ARRAYS IF AVAILABLE

	if($m_zip_dirHandles[$m_zip_original_file]) $m_zip_open_dirs=$m_zip_dirHandles[$m_zip_original_file];
	else $m_zip_open_dirs=$OpenZipFile[2];

	if($m_zip_dirOpenPaths[$m_zip_original_file]) $m_zip_dir_paths=$m_zip_dirOpenPaths[$m_zip_original_file];
	else $m_zip_dir_paths=$OpenZipFile[3];



	$zipDir=UNZIP_DIR.$m_zip_open_file.'/';


	$m_zip_last_open_dir=$m_zip_open_dirs[count($m_zip_open_dirs)-1];
	$m_zip_last_dir_path=$m_zip_dir_paths[count($m_zip_dir_paths)-1];

	//echo($m_zip_last_dir_path.'<br>');
	//print_r($m_zip_dir_paths);
	//echo('<br>');
	do {

		$entryname = readdir($m_zip_last_open_dir);
		$entrypath = $m_zip_last_dir_path.$entryname;
		$exit=TRUE;
		//echo('<p>|<b>'.$entryname.'</b>|<p>');

		if($entryname == '.' || $entryname=='..') {
			$exit=FALSE;
		} elseif($entryname && is_dir($entrypath) ) {
			//echo('new dir: '.$entryname);
			$exit=FALSE;
			$new_dir_path=$entrypath.'/';
			$new_open_dir=opendir($new_dir_path);

			if($new_open_dir) {
				array_push($m_zip_dir_paths,$new_dir_path);
				array_push($m_zip_open_dirs,$new_open_dir);
				$m_zip_last_open_dir=$m_zip_open_dirs[count($m_zip_open_dirs)-1];
				$m_zip_last_dir_path=$m_zip_dir_paths[count($m_zip_dir_paths)-1];
			}

		} elseif(!$entryname && count($m_zip_open_dirs)>1) {

			//echo('leave dir: '.$m_zip_last_dir_path);
			$exit=FALSE;
			closedir($m_zip_last_open_dir);
			array_pop($m_zip_open_dirs);
			array_pop($m_zip_dir_paths);
			$m_zip_last_open_dir=$m_zip_open_dirs[count($m_zip_open_dirs)-1];
			$m_zip_last_dir_path=$m_zip_dir_paths[count($m_zip_dir_paths)-1];
		}

	} while(!$exit);

	if($entryname===FALSE) {

		// NO MORE FILES CLEAR FILEHANDLES AND PATHS ARRAYS

		$m_zip_dirHandles[$m_zip_original_file]=false;
		unset($m_zip_dirHandles[$m_zip_original_file]);
		$m_zip_dirOpenPaths[$m_zip_original_file]=false;
		unset($m_zip_dirOpenPaths[$m_zip_original_file]);


		return false;
	}

	// SAVE FILEHANDLES AND PATHS ARRAYS FOR NEXT RUN

	$m_zip_dirHandles[$m_zip_original_file]=$m_zip_open_dirs;
	$m_zip_dirOpenPaths[$m_zip_original_file]=$m_zip_dir_paths;



	return array($entryname,_m_zip_RelPath($zipDir,$m_zip_last_dir_path),$m_zip_last_dir_path);
}




function _m_zip_RelPath($BasePath,$Path) {
	//echo("BasePath:$BasePath,Path;$Path");
	if($Path==$BasePath) return '';
	if(strpos ($Path,$BasePath)===0) return substr($Path,strlen($BasePath));
	else return $Path;
}





function m_zip_entry_open($zip, $zip_entry, $mode='rb') {
	global $m_zip_fileHandles;

	if(!is_array($zip) || !is_array($zip_entry)) return FALSE;
	if($m_zip_fileHandle) fclose($m_zip_fileHandle);

	$m_zip_fileHandle=fopen ( $zip_entry[2].$zip_entry[0], 'rb');
	if($m_zip_fileHandle!==FALSE) {
		$m_zip_fileHandles[$zip_entry[0]]=$m_zip_fileHandle;
		return TRUE;
	}
	return FALSE;
}

function m_zip_entry_close($zip_entry) {
	global $m_zip_fileHandles;

	if(!is_array($zip_entry)) return FALSE;

	if($m_zip_fileHandles[$zip_entry[0]]) {
		fclose($m_zip_fileHandles[$zip_entry[0]]);
		$m_zip_fileHandles[$zip_entry[0]]=FALSE;
		unset($m_zip_fileHandles[$zip_entry[0]]);
	}

}

function m_zip_entry_name($zip_entry) {
	if(!is_array($zip_entry)) return '';
	else return $zip_entry[1].$zip_entry[0];
}

function m_zip_entry_filesize($zip_entry) {
	clearstatcache();
	return filesize($zip_entry[2].$zip_entry[0]);
}

function m_zip_entry_compressedsize($zip_entry) {
	return '?';
}

function m_zip_entry_compressionmethod($zip_entry) {
	return '?';
}

function m_zip_entry_read($zip_entry, $zip_entry_file_size=0 ) {
	global $m_zip_fileHandles;
	if(!is_array($zip_entry)) return FALSE;

	$TheFile=$zip_entry[2].$zip_entry[0];

	//echo('<p>READING FILE: '.$TheFile);



	// UNCOMMENT THIS TO SPEEDUP. WILL REQUIRE MORE RAM AND FAIL FOR BIG-BIG FILES

	//if($zip_entry_file_size==0 || $zip_entry_file_size==filesize($TheFile)) return implode('',file($TheFile));

	$FH=$m_zip_fileHandles[$zip_entry[0]];
	if($FH) {
		$res=fread($FH, $zip_entry_file_size);
		return $res;
	}
	return FALSE; // Boh? (that in italian means who-knows-if-it-is-right-or-wrong-,-anyway-I-have-serious-perplexities-about-it-all)
}




/// From "User Contributed Notes" at http://it.php.net/manual/en/function.rmdir.php
/// Thanks flexer at cutephp dot com


function m_deldir($aDir) {
	// echo('<p>Deleting:'.$aDir);
	// return; // uncomment to skip deletion (leave things)

	// added support for trailing slash
	if(substr($aDir,-1)=='/') {
		$aDir=substr($aDir,0,-1);
	}

	if (is_dir($aDir)) {
		$current_dir = opendir($aDir);
		while($entryname = readdir($current_dir)) {
			//echo("<br>removing $aDir/$entryname");
			if(is_dir("${aDir}/$entryname") && ($entryname != "." && $entryname!="..")) {
			//echo("<ul>");
				m_deldir("${aDir}/${entryname}");
			//echo("</ul>");
			} elseif($entryname != "." && $entryname!="..") {
				unlink("${aDir}/${entryname}");
			}
		}
		closedir($current_dir);
		rmdir($aDir);
	} else {
		// uncomment this if you want to delete files (use m_deldir on anything)
		// unlink($aDir);
		// comment this if you want to skip warning
		echo('m_deldir() -- <b>Warning!</b> Not a directory: '.$aDir);
	}
}





?>