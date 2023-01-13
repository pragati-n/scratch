<?php

// include ROOT_PATH.'/app/db.php';
include ROOT_PATH.'/config/db.php';

$GLOBALS['db_var']['host'] = $host;
$GLOBALS['db_var']['database'] = $database;
$GLOBALS['db_var']['user'] = $user;
$GLOBALS['db_var']['password'] = $password;

class transaction{
   public $table_transaction = 'tbl_transaction';
   public $table_user = 'tbl_user';
   public $table_scratch_card = ' tbl_scratch_card';
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
	
	public function assign_transactions($params)
	{
	
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$error= array();
		if($params['user_id'] >0 && $params['scratch_id'] >0 )
		{
			$sql ="SELECT id from ".$this->table_user."  where is_active = '1' and id =:ID";
			$r_data = $this->conn->prepare($sql);
			$r_data->bindParam(':ID',$params['user_id']);
			
			$r_data->execute();
			$dataUser = $r_data->fetchAll();
			
			if(!$dataUser[0]['id'])
			{
				$error['msg'][] = "User doesnt exists";
			}
			
			$sql ="SELECT id, discount_amount from ".$this->table_scratch_card."  where is_active = 1 and is_scratched = 0 and id=:ID";
			$r_data = $this->conn->prepare($sql);
			$r_data->bindParam(':ID',$params['scratch_id']);
			$r_data->execute();
			$dataScratch = $r_data->fetchAll();
			
			if(!$dataScratch[0]['id'])
			{
				$error['msg'][] = "scratch card doesnt exists or expired";
			}
			if($dataUser[0]['id'] > 0 && $dataScratch[0]['id'] > 0  && $dataScratch[0]['discount_amount'] >0 )
			{
				$data = ['user_id'=>$dataUser[0]['id'],'scratch_id'=>$dataScratch[0]['id'],'amount'=>$dataScratch[0]['discount_amount'],'trans_date'=>'NOW()'];
		
				$sql = " INSERT INTO  ".$this->table_transaction." (user_id,scratch_id,trans_date,amount)  values (:user_id, :scratch_id, :trans_date,:amount)";
				$res = $this->conn->prepare($sql);
				$res  = $res->execute($data);
				
				/* update scratch card as used - START  */
				$sql = "UPDATE ".$this->table_scratch_card." set is_scratched =1 where id=:ID";
				// echo $param['first_name'];
				$r_data = $this->conn->prepare($sql);
			
				$r_data->bindParam(':ID', $dataScratch[0]['id'] );
				$ret = $r_data->execute();
			
			/* update scratch card as used - END */
			}
		}
		else
		{
			$error['msg'][] = "PLease enter valid user_id and scratch_id";
			
		}
		if(count($error))
		{
			$ret_arr['data'] = implode(",",$error['msg']);
			$ret_arr['success'] = false;
		}
		else
		{
			$ret_arr['data'] = "Tansaction successful";
			$ret_arr['success'] = true;
		}
		return $ret_arr;
	}
  
}