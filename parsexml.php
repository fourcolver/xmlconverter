<?php

include_once('dbconfig.php');
error_reporting(0);

// Parse XML file
if(isset($_POST['parse']) && isset($_POST['url'])) {
	
	// $url_w3 = "https://www.w3schools.com/Xml/note.xml";
	// $url_test = "http://cloud.tfl.gov.uk/TrackerNet/PredictionSummary/V";
	// $url_real = "https://neuvoo.com/services/feeds/generatesV3/generate.php?partner=bebee&country=sg&page=1&of=1";
	// $xml = simplexml_load_file($url_real); //retrieve URL and parse XML content

	$url_real = $_POST['url'];
	// if (filter_var($url_real, FILTER_VALIDATE_URL) === FALSE) {
  //   echo "false"; exit();
	// }

	// try
	// {
	// 	$h = fopen($url_real, 'r');
	// 	$first5000Bytes = fread($h, 5000);
	// 	fclose($h);
	// }
	// catch(\Exception $e)
	// {
	// 	echo "false"; exit();
	// }
	// var_dump($first5000Bytes); exit;

	$reader = new XMLReader();

	if($reader->open($url_real)) {
		$i = 0;
		while($reader->read()) {
			if($reader->nodeType == XMLReader::ELEMENT) $nodeName = $reader->name;
			if($nodeName === "job" || $nodeName === "JOB" || $nodeName === "ad" || $nodeName == "item" || $nodeName == "vacancy" || $nodeName == "Job") {
				$baseTag = [];
				$baseValue = [];
				$cdataTag = [];
				libxml_use_internal_errors(true);
				$readerForNode = str_replace("<![CDATA[", "<![CDATA[cdata", $reader->readOuterXML());
				// print_r($readerForNode); exit;

				try{
					$node = new SimpleXMLElement($readerForNode);
				} catch (Exception $e){
					echo "false"; exit();
				}

				if(!empty($node)) {
					foreach($node as $minikey => $child) {
						
						// foreach($child as $eee) {
						// 	print_r($eee); exit;
						// }
						$tagName = $child->getName();
						// echo $tagName; exit;
						$baseTag[] = $tagName;
						if (strpos($child->__toString(), 'cdata') !== false) {
								$cdataTag[] =$tagName;
								$realString = $xmlString = str_replace("cdata", "", $child->__toString());
								$realString = "<![CDATA[".$realString."]]>";
								$baseValue[] = htmlspecialchars($realString);
						}
						else {
							$baseValue[] = htmlspecialchars($child->__toString());
						}
					}
					$result = ['baseTag' => $baseTag, 'baseValue' => $baseValue, 'cdataTag' => $cdataTag];
					echo json_encode($result); exit();
				}
				else {
					echo "false"; exit();
				}

			}
			$i ++;
			if($i > 100) {
				echo $i; exit;
			}
		}
	}

	
	/*

	$baseDetailJob = preg_match('/<job>(.*?)<\/job>/s', $first5000Bytes, $matchJob);
	$baseDetailItem = preg_match('/<item>(.*?)<\/item>/s', $first5000Bytes, $matchItem);

	switch (true) {
		case (!empty($baseDetailJob)):
				$baseHandleContent = $baseDetailJob;
				$match = $matchJob[0];
				break;
	
		case (!empty($baseDetailItem)):
				$baseHandleContent = $baseDetailItem;
				$match = $matchItem[0];
				break;
	
		default:
				$baseHandleContent = [];
				break;
	}

	if(empty($baseHandleContent)) {
		echo "false"; exit();
	}

	$xmlString = "<?xml version='1.0' encoding='UTF-8'?>".$match;
	$xmlString = str_replace("<![CDATA[", "<![CDATA[cdata", $xmlString);
	$baseDetailXml=simplexml_load_string($xmlString);
	
	if($baseDetailXml->getName() == "job" || $baseDetailXml->getName() == "item") {
		$baseTag = [];
		$baseValue = [];
		$cdataTag = [];
		foreach ($baseDetailXml as $child)
		{
			$tagName = $child->getName();
			$baseTag[] = $tagName;
			if (strpos($child->__toString(), 'cdata') !== false) {
				$cdataTag[] =$tagName;
			    $realString = $xmlString = str_replace("cdata", "", $child->__toString());
			    $realString = "<![CDATA[".$realString."]]>";
			    $baseValue[] = htmlspecialchars($realString);
			}
			else {
				$baseValue[] = $child->__toString();
			}
		}
		
		$result = ['baseTag' => $baseTag, 'baseValue' => $baseValue, 'cdataTag' => $cdataTag];
		echo json_encode($result); exit();
	}
	else {
		echo "false"; exit();	
	}
	*/
}

// Save XML information
if(isset($_POST['saveFeed'])) {
	$name = $_POST['feedName'];
	$xmlurl = $_POST['xmlurl'];
	$basetag = $_POST['basetag'];
	$updatetag = $_POST['updatetag'];
	$cdatatag = $_POST['cdatatag'];
	$willAddCountry = $_POST['willAddCountry'];
	$jobLocationType = $_POST['jobLocationType'];
	$defaultCountry = ($willAddCountry == "invalid") ? null : $willAddCountry;
	$joblocationtype = ($jobLocationType == "invalid") ? null : $jobLocationType;
	$crudResult = $crud->create($name, $xmlurl, $basetag, $updatetag, $cdatatag, $defaultCountry, $joblocationtype);
	if($crudResult == true) {
		$res = json_encode(['data' => "true"]);
	}
	else {
		$res = json_encode(['data' => "false"]);
	}
	echo $res; exit();
}

// Remove XML information
if(isset($_POST["removeItem"])) {
	$data_id = $_POST['data_id'];
	$crudResult = $crud->delete($data_id);
	if($crudResult == true) {
		echo "true"; exit();
	}
}

// running XML directly
if(isset($_POST["runningItem"])) {
	$data_id = $_POST['data_id'];
	$crudResult = $crud->createRunning($data_id);
	if($crudResult === true) {
		$res = json_encode(['data' => "true"]);
	}
	else if($crudResult === "warning") {
		$res = json_encode(['data' => "warning"]);
	}
	else {
		$res = json_encode(['data' => "false"]);
	}
	echo $res; exit();
}

// Update xml information
if(isset($_POST['updateFeed'])) {
	$id = $_POST['id'];
	$name = $_POST['feedName'];
	$updatetag = $_POST['updatetag'];
	$xmlurl = $_POST['xmlurl'];
	$willAddCountry = $_POST['willAddCountry'];
	$jobLocationType = $_POST['jobLocationType'];
	$defaultCountry = ($willAddCountry == "invalid") ? null : $willAddCountry;
	$joblocationtype = ($jobLocationType == "invalid") ? null : $jobLocationType;
	$crudResult = $crud->update($id, $name, $updatetag, $xmlurl, $defaultCountry, $joblocationtype);
	if($crudResult == true) {
		$res = json_encode(['data' => "true"]);
	}
	else {
		$res = json_encode(['data' => "false"]);
	}
	echo $res; exit();
}

?>