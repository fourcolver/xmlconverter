<?php
include_once 'dbconfigcron.php';

$getRunning = $crud->getRunningChecking();

if(count($getRunning) > 0) {

  $makingProgress = $crud->setCheckToProgress($getRunning);

  if($makingProgress) {
    $feedAll = $crud->getRunningItem($getRunning);
  
    if(count($feedAll) > 0) {
      
      foreach ($feedAll as $value) {
    
        $cdatatagpiece = [];
        $updatetag = $value['updatetag'];
        $basetag = $value['basetag'];
        $updatetagpiece = explode(",", $updatetag);
        $basetagpiece = explode(",", $basetag);
        $defaultcountry = $value['defaultcountry'];
        if($value['cdatatag'] != "") {
          $cdatatagpiece = explode(",", $value['cdatatag']);
        }
    
        $reader = new XMLReader();
    
        if($reader->open($value['url'])) {
    
          $key = 0;
    
          $saveName = str_replace(" ", "_", strtolower($value['name'])).".xml";
          $saveName = "xmldir/".$saveName;
    
          if (file_exists($saveName)) {
            $deleted = unlink($saveName);
          }
    
    
          $xmlWriter = new XMLWriter();
          $xmlWriter->openMemory();
          $xmlWriter->startDocument('1.0', 'UTF-8');
          $xmlWriter->setIndent(TRUE);
          $xmlWriter->startElement('bebee');
    
          while($reader->read()) {
    
            if($reader->nodeType == XMLReader::ELEMENT) $nodeName = $reader->name;
      
            if($nodeName === "job" || $nodeName === "JOB" || $nodeName === "ad" || $nodeName == "item" || $nodeName == "vacancy" || $nodeName == "Job") {
      
              libxml_use_internal_errors(true);
              
              try{
                  $node = new SimpleXMLElement($reader->readOuterXML());
              } catch (Exception $e){
                continue;
              }
      
              if(!empty($node)) {
                
                $xmlWriter->startElement('item');
      
                $i = 0;
                foreach($node as $minikey => $minichild) {
                  if(!empty($updatetagpiece[$i])) {
                    if($updatetagpiece[$i] != "discard") {
                      if($updatetagpiece[$i] != "Default") {
                        $updatetagReal = $updatetagpiece[$i];
                      }
                      else {
                        $updatetagReal = $minichild->getName();
                      }
      
                      if(in_array($minichild->getName(), $cdatatagpiece)) {
                        $xmlWriter->startElement($updatetagReal);
                          $xmlWriter->writeCdata(htmlspecialchars($minichild->__toString()));
                        $xmlWriter->endElement();
                      }
                      else {
                        $xmlWriter->writeElement($updatetagReal, htmlspecialchars($minichild->__toString()));
                      }
                    }
                    $i ++ ;
                  }
                }
                if(!empty($defaultcountry)) {
                  $xmlWriter->writeElement("addressCountry", $defaultcountry);
                }
                if(!empty($joblocationtype)) {
                  $xmlWriter->writeElement("jobLocationType", $joblocationtype);
                }
                $xmlWriter->endElement();
              }
              
            }
            $key ++ ;
            if (0 == $key%1000) {
                file_put_contents($saveName, $xmlWriter->flush(true), FILE_APPEND);
            }
    
          }
          $xmlWriter->endElement();
          file_put_contents($saveName, $xmlWriter->flush(true), FILE_APPEND);
        }
        $deleteResult = $crud->deleteChecking($value['id']);
      }
      echo "success";
    }
  }
}

?>