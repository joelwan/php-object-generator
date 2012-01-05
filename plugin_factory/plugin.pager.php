<?php
class pager
{
	var $sourceObject;
	var $argv;
	var $version = '0.1';

	private $total_records;
	private $start = 0;
	private $limit = 50;
	private $pages;
    private $thispage =1;
	private $objectlist = null;
    private $pg;
    private $obj;
	function Version()
	{
		return $this->version;
	}

	function pager($sourceObject, $argv)
	{
		$this->sourceObject = $sourceObject;
		$this->argv = $argv;
		if (sizeof($this->argv) > 0){
			$fcv_array = $this->argv[0];
			if (sizeof($fcv_array) > 2){
				$this->thispage = $fcv_array[1];                
				$this->limit = $fcv_array[2];
			}
		}
        if($this->thispage<=0){
            $this->thispage = 1;
        }
	}
	
	function Execute()
	{
		$this->total_records = $this->getRowCount();
        if($this->total_records <= $this->limit){
            $this->thispage = 1;
        }
        if($this->thispage > $this->getTotalPages()){
            $this->thispage = $this->getTotalPages();
        }
        $this->start = ($this->thispage-1)*$this->limit;
        
		return $this;
	}

	function getTotalrows(){
		return $this->total_records;
	}
	
	function getCurrentPage(){
		return $this->thispage;
	}
	
	function needPagination(){
		if (( $this->total_records != 0) && ( $this->total_records > $this->limit))
			return true;
		else
			return false;
	}
	
	function getTotalPages(){
        if($this->total_records>0){
            $this->pages = ceil($this->total_records/$this->limit);    
        }
        else{
            $this->pages = 1;
        }
		
		return $this->pages;
	}

	function getRecords(){
		$objectName = get_class($this->sourceObject);
		$fcv_array = array();
		if (sizeof($this->argv) > 0)
		{
			$fcv_array = $this->argv[0][0];
		}
		$pg = $this->start.','.$this->limit;
		eval('$this->obj = new $objectName();');
		$this->objectlist = $this->obj->GetList($fcv_array,'',true,$pg);
		return $this->objectlist;
	}
	
    public function getPrev() {
		if ($this->thispage != 1) {
		      $prev = $this->thispage - 1;
		} else {
		      $prev = false;
		}
		return $prev;
	}
    
    public function getPrevLinks($range = 5)
	{
		/*$total = $this->getTotalPages();*/
		$start = $this->thispage - 1;
		$end = $this->thispage - $range;
		$first =  1;
		$links = array();
		for ($i=$start; $i>$end; $i--) {
			if ($i < $first) {
					break;
			}
			$links[] = $i;
		}

		return array_reverse($links);
	}
    
    public function getNext() {
		if ($this->thispage != $this->getTotalPages()) {
		      $next = $this->thispage + 1;
		} else {
		      $next = false;
		}
		return $next;
	}
    
    public function getNextLinks($range = 5)
	{
		/*$total = $this->getTotalPages();*/
		$start = $this->thispage + 1;
		$end = $this->thispage + $range;
		$last =  $this->pages;
		$links = array();
		for ($i=$start; $i<$end; $i++) {
			if ($i > $last) {
					break;
			}
			$links[] = $i;
		}

		return $links;
	}
    
	private function getRowCount(){
		$objectName = get_class($this->sourceObject);
		if (sizeof($this->argv) > 0)
		{
			$fcv_array = $this->argv[0][0];
		}
		$sql = 'select count(*) as mycount from `'.strtolower($objectName).'`';

		if (isset($fcv_array) && sizeof($fcv_array) > 0)
		{
			$sql .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$sql .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$sql .= " AND ";
					}
					if (isset($this->sourceObject->pog_attribute_type[$fcv_array[$i][0]]) && $this->sourceObject->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->sourceObject->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$sql .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".POG_Base::Escape($fcv_array[$i][2])."'";
							$sql .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$sql .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}


		$connection = Database::Connect();
		$cursor = Database::Reader($sql, $connection);
		while ($row = Database::Read($cursor))
		{
			$count = $row['mycount'];
		}
		return $count;
	}
	
	function SetupRender()
	{
		if ($this->PerformUnitTest() === false)
		{
			echo get_class($this).' failed unit test';
		}
		else
		{
			echo get_class($this).' passed unit test';
		}
	}

	function AuthorPage()
	{
		return null;
	}

	function PerformUnitTest()
	{
		//test w/o arguments
		//any object
		$objectNames = unserialize($_SESSION['objectNameList']);

		//try getting a count
		if (sizeof($objectNames) > 0)
		{
			$anyObject = $objectNames[0];
			include_once("../objects/class.".strtolower($anyObject).".php");
			$anyObjectInstance = new $anyObject();
			$count = $anyObjectInstance->GetCount();

			$count2 = 0;
			$sql = 'select count(*) from `'.strtolower($anyObject).'`;';
			$connection = Database::Connect();
			$cursor = Database::Reader($sql, $connection);
			if ($cursor !== false)
			{
				while($row = Database::Read($cursor))
				{
					$count2 = $row['count(*)'];
				}
			}

			if ($count == $count2)
			{
				return true;
			}
			return false;

		}

		//test w/ arguments


	}
}
