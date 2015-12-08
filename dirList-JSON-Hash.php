#!/usr/bin/php
<?php

/*
  dirList-JSON-Hash is Command line tool for directory listing with JSON format, hash(MD5,CRC32,SHA1,SHA256,SHA512) file support and file information. Usefull for generate list of update for application updater.

  Developed by aancw < cacaddv[at]gmail[dot]com >

  Some code taken from http://zurb.com/forrst/posts/Generate_a_JSON_list_based_on_files_in_a_directo-GDc
  Thanks to Jason Gerfen( https://github.com/jas- )

*/

// Set timezone fot date function
date_default_timezone_set('GMT');

/*
 * @name getList
 * @param Array $dir
 * @param Array $types
 * @abstract Recursively iterates over specified directory
 *           populating array based on array of file extensions
 * @return Array $files
 */
function getList($dir)
{
    $it = new RecursiveDirectoryIterator($dir);
    foreach(new RecursiveIteratorIterator($it) as $file)
    {
            $files[] = $file->__toString();
    }
    return $files;
}

/*
 * @name getDetails
 * @param Array $dir
 * @param Array $types
 * @abstract Recursively iterates over specified directory
 *           populating array with details of each file
 * @return Array $files
 */
function getDetails($array, $useHash)
{
        foreach($array as $file)
        {
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $files[basename($file)]['location'] = $file;
          $files[basename($file)]['type'] = finfo_file($finfo, $file);
          $files[basename($file)]['size'] = filesize($file);
          $files[basename($file)]['last_modified'] = date ("F d Y H:i:s", filemtime($file));

          if($useHash == true){
            $files[basename($file)]['md5'] = md5_file($file);
            $files[basename($file)]['crc32'] = hash_file('crc32', $file);
            $files[basename($file)]['sha1'] = hash_file('sha1', $file);
            $files[basename($file)]['sha256'] = hash_file('sha256', $file);
            $files[basename($file)]['sha512'] = hash_file('sha512', $file);
          }

          finfo_close($finfo);
        }
    return array('files'=>$files);
}

if (!function_exists('json_encode')) {

    /*
     * @name json_encode
     * @param Mixed $val
     * @abstract Alternate emulated json_encode function
     * @return Object $res
     */
    function json_encode($val)
    {
        if (is_string($val)) return '"'.addslashes($val).'"';
        if (is_numeric($val)) return $val;
        if ($val === null) return 'null';
        if ($val === true) return 'true';
        if ($val === false) return 'false';

        $assoc = false;
        $i = 0;
        foreach ($val as $k=>$v){
            if ($k !== $i++){
                $assoc = true;
                break;
            }
        }
        $res = array();
        foreach ($val as $k=>$v){
            $v = json_encode($v);
            if ($assoc){
                $k = '"'.addslashes($k).'"';
                $v = $k.':'.$v;
            }
            $res[] = $v;
        }
        $res = implode(',', $res);
        return ($assoc)? '{'.$res.'}' : '['.$res.']';
    }
}
function showHelp()
{
  echo "\n";
  echo "dirList-JSON-Hash by aancw\n\n";
  echo "dirList-JSON-Hash is Command line tool for directory listing with JSON format, hash(MD5,CRC32,SHA1,SHA256,SHA512) file support and file information. Usefull for generate list of update file for application updater.\n\n";
  echo "Usage: php dirList-JSON-Hash [folderpath] [options]\n";
  echo "Options:\n";
  echo "-hash : Include hash file output in JSON\n";
  echo "Bug reports, feedback, admiration, abuse, etc, to: cacaddv[at]gmail[dot]com\n";
  exit;
}

if( $argc < 2 )
{
  showHelp();
}else{

  if (!isset($argv[1]))
      exit("Must specify a directory to scan\n");

  if (!is_dir($argv[1]))
      exit($argv[1]."' is not a directory\n");

  echo "\ndirList-JSON-Hash by aancw\n\n";

  $useHash = false;

  for ($i = 1; $i < $argc; $i++)
  {
    if($argv[$i] == "-hash"){
      $useHash = true;
    }
  }

  echo "Please be patient because sometime it take long time depend on how big your directory size :)\n";

  $outputJSON = json_encode( getDetails( getList($argv[1]), $useHash ), JSON_PRETTY_PRINT);
  $filename = "output-". date("Ymd-His") . ".json";
  $fh = fopen($filename, 'w');
  fwrite($fh, $outputJSON);
  fclose($fh);

  echo "Done! Your JSON has been saved to " . $filename. "\n";
}
