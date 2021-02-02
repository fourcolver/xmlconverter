<?php

include_once('dbconfig.php');
error_reporting(E_ALL);

// Parse XML file
if(isset($_POST['parse']) && isset($_POST['url'])) {
	
	$url_real = $_POST['url'];

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

	if (strpos($url_real, '.zip') !== false || strpos($url_real, '.gz') !== false) {
		$isReady = $crud->getIsReady($url_real);
		if($isReady) {
			$url_real = "tempzip/".$isReady['name'].'.xml';
		}
		else {
			$res = json_encode(['data' => "false"]);
			echo $res; exit();
		}
	}

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

				// var_dump($node); exit;

				if(!empty($node)) {
					foreach($node as $minikey => $child) {

						// $status = false;
						// foreach($child as $miniChild) {
						// 	$tagName = $child->getName();
						// 	if(!empty($miniChild->getName())) {


						// 		// foreach($miniChild as $miminiChild) {
						// 		// 	if(!empty($miminiChild->getName())) {
						// 		// 		echo $miminiChild->__toString(); exit;
						// 		// 	}
						// 		// }


						// 		$status = true;
						// 		$addTagName = $tagName.":".$miniChild->getName();
						// 		$baseTag[] = $addTagName;
						// 		if (strpos($miniChild->__toString(), 'cdata') !== false) {
						// 			$cdataTag[] =$tagName;
						// 			$realString = $xmlString = str_replace("cdata", "", $miniChild->__toString());
						// 			$realString = "<![CDATA[".$realString."]]>";
						// 			$baseValue[] = htmlspecialchars($realString);
						// 		}
						// 		else {
						// 			$baseValue[] = htmlspecialchars($miniChild->__toString());
						// 		}
						// 	}
						// }

						// if($status) {
						// 	continue;
						// }

						$tagName = $child->getName();
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

//save downloadfile information
if(isset($_POST["downloadfile"])) {
	$xmlurl = $_POST['xmlurl'];
	$crudResult = $crud->createDownloading($xmlurl);

	if($crudResult === "warning") {
		$res = json_encode(['data' => "warning"]);
	}
	elseif($crudResult === false) {
		$res = json_encode(['data' => "false"]);
	}
	else {
		$res = json_encode(['data' => "true"]);
	}
	echo $res; exit();
}

// Remove XML information
if(isset($_POST["removeFile"])) {
	$data_id = $_POST['data_id'];
	$crudResult = $crud->deleteFile($data_id);
	if($crudResult == true) {
		echo "true"; exit();
	}
}

