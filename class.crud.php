<?php

class crud
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function create($name,$url,$basetag,$updatetag, $cdatatag, $defaultcountry, $joblocationtype)
	{
		$now = new DateTime();
		$createdate = $now->format('Y-m-d H:i:s');
		try
		{
			$stmt = $this->db->prepare(
				"INSERT INTO feedinfo(name,url,basetag,updatetag,cdatatag,createdate,updatedate,defaultcountry,joblocationtype) 
						VALUES(:name, :url, :basetag, :updatetag, :cdatatag, :createdate, :updatedate, :defaultcountry, :joblocationtype)");
			$stmt->bindparam(":name",$name);
			$stmt->bindparam(":url",$url);
			$stmt->bindparam(":basetag",$basetag);
			$stmt->bindparam(":updatetag",$updatetag);
			$stmt->bindparam(":cdatatag",$cdatatag);
			$stmt->bindparam(":createdate",$createdate);
			$stmt->bindparam(":updatedate",$createdate);
			$stmt->bindparam(":defaultcountry",$defaultcountry);
			$stmt->bindparam(":joblocationtype",$joblocationtype);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}
	
	public function getID($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM feedinfo WHERE id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}

	public function getAll() {
		$feedAll = [];
		$stmt = $this->db->prepare("SELECT * FROM feedinfo");
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$feedAll[] = $row;
		}
		return $feedAll;
	}

	public function update($id,$name,$updatetag,$xmlurl,$defaultcountry,$joblocationtype)
	{
		$now = new DateTime();
		$updateDate = $now->format('Y-m-d H:i:s');
		try
		{
			$stmt=$this->db->prepare("UPDATE feedinfo SET name=:fname, 
		                                               updatetag=:updatetag, updatedate=:updatedate,
																									 url=:xmlurl, defaultcountry=:defaultcountry, joblocationtype=:joblocationtype
													WHERE id=:id ");
			$stmt->bindparam(":fname",$name);
			$stmt->bindparam(":updatedate",$updateDate);
			$stmt->bindparam(":updatetag",$updatetag);
			$stmt->bindparam(":id",$id);
			$stmt->bindparam(":xmlurl",$xmlurl);
			$stmt->bindparam(":defaultcountry",$defaultcountry);
			$stmt->bindparam(":joblocationtype",$joblocationtype);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}
	
	public function delete($id)
	{
		$stmt = $this->db->prepare("DELETE FROM feedinfo WHERE id=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		return true;
	}

	//initial xml making
	public function createRunning($id) {
		$is_stmt = $this->db->prepare("SELECT * FROM running WHERE feedid=:feedid");
		$is_stmt->bindparam(":feedid",$id);
		$is_stmt->execute();
		if($is_stmt->rowCount() > 0) {
			return "warning";
		}
		else {
			try
			{
				$status = "Checking";
				$stmt = $this->db->prepare(
					"INSERT INTO running(feedid, status)
							VALUES(:feedid, :status)");
				$stmt->bindparam(":feedid",$id);
				$stmt->bindparam(":status",$status);
				$stmt->execute();
				return true;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();	
				return false;
			}
		}
	}

	//Generate random string
	public function generateRandomString($length = 8) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
	}

	// save downloadfile information
	public function createDownloading($url) {
		$is_stmt = $this->db->prepare("SELECT * FROM filexml WHERE inputurl=:url");
		$is_stmt->bindparam(":url",$url);
		$is_stmt->execute();
		if($is_stmt->rowCount() > 0) {
			return "warning";
		}
		else {
			try
			{
				$name = $this->generateRandomString(8);
				$status = "Downloading";
				$stmt = $this->db->prepare(
					"INSERT INTO filexml(inputurl, name, status)
							VALUES(:inputurl, :name, :status)");
				$stmt->bindparam(":inputurl",$url);
				$stmt->bindparam(":name",$name);
				$stmt->bindparam(":status",$status);
				$stmt->execute();
				return $name;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();	
				return false;
			}
		}
	}

	// Get download file information
	public function getDownloading() {
		$runningList = [];
		$downloadingId = [];
		$status = "Downloading";
		$stmt = $this->db->prepare("SELECT * FROM filexml WHERE status=:status");
		$stmt->bindparam(":status", $status);
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$runningList[] = $row;
			$downloadingId[] = $row['id'];
		}
		$result = ['runningList' => $runningList, 'downloadingId' => $downloadingId];
		return $result;
	}

	//remove downloadfile information
	public function deleteFile($id)
	{
		$stmt = $this->db->prepare("DELETE FROM filexml WHERE id=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		return true;
	}

	// download to ready status change
	public function setDownToReady($id) {
		try{
			$status = "Ready";
			$stmt = $this->db->prepare("UPDATE filexml SET status=:status
																	WHERE id=:id");
			$stmt->bindparam(":status",$status);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}

	// download to ready status change
	public function setDownToError($id) {
		try{
			$status = "Error";
			$stmt = $this->db->prepare("UPDATE filexml SET status=:status
																	WHERE id=:id");
			$stmt->bindparam(":status",$status);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}

	// download to ready status change
	public function setDownToDownloading($id) {
		try{
			$status = "Downloading";
			$stmt = $this->db->prepare("UPDATE filexml SET status=:status
																	WHERE id=:id");
			$stmt->bindparam(":status",$status);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}

	// get row with inputurl
	public function getIsReady($url) {
		$status = "Ready";
		$stmt = $this->db->prepare("SELECT * FROM filexml WHERE status=:status AND inputurl=:url");
		$stmt->bindparam(":status", $status);
		$stmt->bindparam(":url", $url);
		$stmt->execute();
		if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $row;
		}
		else {
			return false;
		}
	}

	// Set download to Progress
	public function setDownToProgress($idList) {
		try{
			$in  = str_repeat('?,', count($idList) - 1) . '?';
			$stmt = $this->db->prepare("UPDATE filexml SET status='Progressing'
																	WHERE id IN ($in)");
			$stmt->execute($idList);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}

	public function getRunning() {
		$runningList = [];
		$runningStatus = [];
		$query = "SELECT * FROM running";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$runningList[] = $row['feedid'];
				$runningStatus[] = $row['status'];
			}
		}
		$result = ['runningList' => $runningList, 'runningStatus' => $runningStatus];
		return $result;
	}

	public function getRunningChecking() {
		$runningList = [];
		$status = "Checking";
		$stmt = $this->db->prepare("SELECT * FROM running WHERE status=:status");
		$stmt->bindparam(":status", $status);
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$runningList[] = $row['feedid'];
		}
		return $runningList;
	}

	public function getRunningItem($list) {
		$runningList = [];
		$in  = str_repeat('?,', count($list) - 1) . '?';
		$stmt = $this->db->prepare("SELECT * FROM feedinfo WHERE id IN ($in)");
		$stmt->execute($list);
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$runningList[] = $row;
		}
		return $runningList;
	}

	public function deleteChecking($id) {
		$stmt = $this->db->prepare("DELETE FROM running WHERE feedid=:feedid");
		$stmt->bindparam(":feedid",$id);
		$stmt->execute();
		return true;
	}

	public function setCheckToProgress($list) {
		try{
			$in  = str_repeat('?,', count($list) - 1) . '?';
			$stmt = $this->db->prepare("UPDATE running SET status='Progressing'
																	WHERE feedid IN ($in)");
			$stmt->execute($list);
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}

	public function modifyTag($willUpdateTag, $id) {
		try{
			$stmt = $this->db->prepare("UPDATE feedinfo SET updatetag=:updatetag
																	WHERE id=:id");
			$stmt->bindparam(":updatetag",$willUpdateTag);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();	
			return false;
		}
	}

	public function dataFileView($query) {
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		if($stmt->rowCount() > 0)
		{
			$returnString = "";

			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				if($row['status'] == "Downloading") {
					$class = "text-green";
				}
				else if($row['status'] == "Ready" || $row['status'] == "Progressing") {
					$class = "text-blue";
				}
				else {
					$class = "text-red";
				}
				$returnString .= "
					<tr>
						<td>".$row['name']."</td>
						<td>".$row['inputurl']."</td>
						<td class='".$class."'>".$row['status']."</td>
						<td><a href='#' date-id='".$row['id']."' class='btn btn-default btn-sm mr-1 removeFile'  data-toggle='modal' data-target='#removeModal'><i class='far fa-trash-alt'></i></a></td>
					</tr>
				";
			}
			return $returnString;
		}
	}
		
	public function dataview($query)
	{
		$runningInfo = $this->getRunning();
		$main_url = "https://converter.bebee.com/cf/xmldir/";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		if($stmt->rowCount() > 0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$outputUrl = $main_url.str_replace(" ", "_", strtolower($row['name'])).".xml";
				$filePath = str_replace(" ", "_", strtolower($row['name'])).".xml";
				?>
					<tr>
						<td><?php echo($row['name']); ?></td>
						<td style="width: 300px; word-break: break-word;"><?php echo($row['url']); ?></td>
						<td style="width: 300px; word-break: break-word;"><?php echo $outputUrl; ?></td>
						<td><?php echo($row['createdate']); ?></td>
						<td><?php echo($row['updatedate']); ?></td>
						<?php
							if(in_array($row['id'], $runningInfo['runningList'])) {
								$key = array_search($row['id'], $runningInfo['runningList']);
								echo "<td class='font-red'>".$runningInfo['runningStatus'][$key]."</td>";
							}
							else {
								$filename = "xmldir/".$filePath;
								if (file_exists($filename)) {
										echo "<td>".date ("Y-m-d H:i:s", filemtime($filename))."</td>";
								}
								else {
									echo "<td>Not Converted Yet</td>";
								}
							}					
						?>
						<td>
							<a href="edit-data.php?edit_id=<?php echo($row['id']); ?>" class="btn btn-default btn-sm mr-1"><i class="fas fa-pen"></i></a>
							<a href="#" data-id="<?php echo($row['id'])?>" class="btn btn-default btn-sm mr-1 removeXml"  data-toggle="modal" data-target="#removeModal"><i class="far fa-trash-alt"></i></a>
							<a href="#" data-id="<?php echo($row['id'])?>" class="btn btn-default btn-sm mr-1 runningXml" data-toggle="modal" data-target="#runningModal"><i class="fas fa-download"></i></a>
						</td>
					</tr>
					<?php
			}
		}
	}
}
?>