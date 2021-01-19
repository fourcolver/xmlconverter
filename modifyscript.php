<?php
include_once 'dbconfigcron.php';

$feedAll = $crud->getAll();

if(count($feedAll) > 0) {
  
  // $xml = new DOMDocument();
  // $xml->formatOutput=true;  
  // $xml_bebee = $xml->createElement("bebee");
  // $xml->appendChild( $xml_bebee );
  
  foreach ($feedAll as $value) {

    $updatetag = $value['updatetag'];
    $id = $value['id'];
    if(strpos($updatetag, "country") !== false){
      $willUpdateTag = str_replace("country","addressCountry",$updatetag);
      $updateResult = $crud->modifyTag($willUpdateTag, $id);
      echo $updateResult;
    }
  }
    
}

?>