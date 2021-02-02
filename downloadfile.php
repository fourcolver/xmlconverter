<?php
include_once 'dbconfigcron.php';

set_time_limit(0);
ini_set('memory_limit', '-1');

$getRunning = $crud->getDownloading();
$runningList = $getRunning['runningList'];
$downloadingId = $getRunning['downloadingId'];

if(empty($_SESSION['bigCron'])) {
  if(count($runningList) > 0) {
    
    // make db "downloading" to "progressing"
    $progressCrud = $crud->setDownToProgress($downloadingId);
    foreach ($runningList as $value) {
  
      if (strpos($value['inputurl'], '.zip') !== false) {
        $file = $value['inputurl'];
        $newfile = 'tempzip/'.$value['name'].'.zip';
        // if (!copy($file, $newfile)) {
        //     echo "failed to copy $file...\n";
        // }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $file);
        curl_setopt($ch, CURLOPT_USERAGENT, "TETRA 4.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // $header = substr($response, 0, $header_size);
        curl_close($ch);

        if($httpcode == 200) {
          $body = substr($response, $header_size);
          $fp = fopen($newfile, 'wb');
          fwrite($fp, $body);
          fclose($fp);

          // file extract
          $zip = new ZipArchive;
          $res = $zip->open($newfile);
          if ($res === TRUE) {
            $inName = $zip->getNameIndex(0);
            $zip->extractTo('tempzip/');
            $zip->close();
            rename('tempzip/'.$inName, 'tempzip/'.$value['name'].'.xml');
            // make db "downloading" to "ready"
            $readyCrud = $crud->setDownToReady($value['id']);
            unlink($newfile);
          }
        }
        else {
          $readyCrud = $crud->setDownToError($value['id']);
        }
      }
  
      if (strpos($value['inputurl'], '.gz') !== false) {
        $file = $value['inputurl'];
        $newfile = 'tempzip/'.$value['name'].'.gz';
        // if (!copy($file, $newfile)) {
        //     echo "failed to copy $file...\n";
        // }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $file);
        curl_setopt($ch, CURLOPT_USERAGENT, "TETRA 4.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // $header = substr($response, 0, $header_size);
        curl_close($ch);

        if($httpcode == 200) {
          $body = substr($response, $header_size);
          $fp = fopen($newfile, 'wb');
          fwrite($fp, $body);
          fclose($fp);

          $buffer_size = 8096; // The number of bytes that needs to be read at a specific time, 4KB here
          $out_file_name = str_replace('.gz', '.xml', $newfile);
          $file = gzopen($newfile, 'rb'); //Opening the file in binary mode
          if($file) {
            $out_file = fopen($out_file_name, 'wb');
            // Keep repeating until the end of the input file
            while (!gzeof($file)) {
              fwrite($out_file, gzread($file, $buffer_size)); //Read buffer-size bytes.
            }
            fclose($out_file); //Close the files once they are done with
            gzclose($file);
            // make db "downloading" to "ready"
            $readyCrud = $crud->setDownToReady($value['id']);
            unlink($newfile);
          }
        }
        else {
          $readyCrud = $crud->setDownToError($value['id']);
        }
      }
    }
    echo "success";
  }
}

?>