<?php

// include ROOT_PATH.'/app/db.php';
include ROOT_PATH.'/config/db.php';

$GLOBALS['db_var']['host'] = $host;
$GLOBALS['db_var']['database'] = $database;
$GLOBALS['db_var']['user'] = $user;
$GLOBALS['db_var']['password'] = $password;

class scratch{
   public $table_name = 'tbl_scratch_card';
   public $conn ;
	
	public function __construct()
	{
		global $db_var;
		$db = new db($db_var);
		$this->conn = $db->connect();
	}
	
	/* public function generate_scratch_cards($params)
	{
		$rdata = $this->validate_number($params);
	} */
	
	public function generate_scratch_cards($params)
	{
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		if($params['num'] > 0)
		{
			$sql ="SELECT count(id) as total_num from tbl_scratch_card  where is_active = 1 and 	is_scratched  = 0 ";
			$r_data = $this->conn->prepare($sql);
			$r_data->execute();
			$data_count = $r_data->fetchAll();
			// $number_of_rows = $r_data->fetchColumn(); 
			// echo $data[0]['total_num']	;exit;
			$rdata = array();
			if($data_count[0]['total_num'] >= $params['num'])
			{
				$rdata['error'] = $params['num']." number of active scratch cards still exists in the DB. Did not create any new scratch cards ";
			}
			else
			{
				// echo $data_count[0]['total_num'];
				for($i=0;$i<$params['num'];$i++)
				{
				
					$data['discount_amount'] = rand(1,1000);
					$data['scratch_code'] = time();
					$data['is_scratched'] = 0;
					$data['is_active'] = 1;
				
					$sql = " INSERT INTO  ".$this->table_name." (scratch_code,discount_amount,is_scratched,is_active)  values (:scratch_code, :discount_amount, :is_scratched,:is_active)";
					$res = $this->conn->prepare($sql);
					$res  = $res->execute($data);
					unset($data);
				}
				$ret_arr['code'] = 200;
				$ret_arr['success'] = true;
				$ret_arr['data'] = "Scratch cards are created in to the system";
			}
		}
		else
		{
			$rdata['error'] = "Please enter valid number";
		}
		
		if(count($rdata['error'] )>0)
		{
			$ret_arr['data'] = implode(",",$rdata);
			$ret_arr['success'] = false;
		}
		return $ret_arr;
	}
  
}