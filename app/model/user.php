<?php

include ROOT_PATH.'/app/db.php';
include ROOT_PATH.'/config/db.php';

$GLOBALS['db_var']['host'] = $host;
$GLOBALS['db_var']['database'] = $database;
$GLOBALS['db_var']['user'] = $user;
$GLOBALS['db_var']['password'] = $password;

class user{
   public $table_name = 'tbl_user';
   public $conn ;
	
	public function __construct()
	{
		global $db_var;
		$db = new db($db_var);
		$this->conn = $db->connect();
	}
   public function fetch_user_data()
   {
		
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$sql ="SELECT * from tbl_user  where is_active = 1 ";
		// if($id>0)
		// {
			// $sql .= " and id=:ID";
		// }
		$r_data = $this->conn->prepare($sql);
	
		// if($id>0)
		// {
			// $r_data->bindParam(':ID',$id);
		// }
		$r_data->execute();
		$data = $r_data->fetchAll();
		if(count($data) > 0)
		{
			$ret_arr['success'] = true;
			foreach($data as $key => $val)
			{
				$rdata[$key]['id'] =   $val['id'];
				$rdata[$key]['first_name'] =   $val['first_name'];
				$rdata[$key]['last_name'] =   $val['last_name'];
				$rdata[$key]['email'] =   $val['email'];
				$rdata[$key]['is_active'] =   $val['is_active'];
			}
			$ret_arr['data']= $rdata ;
			// print_r($ret_arr);
			
		}
		else
		{
			$ret_arr['success'] = false;
			$ret_arr['data'] = false;
			
		}
		 // $ret_arr = array( 'success' => $response['success'] ?? true,
				// 'code' =>$response['code'],
                 // 'data' => $response['data'],
		// print_r($data);
		return $ret_arr;
	
	   
   }
   
   public function create_user_data($params)
   {
		
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		
		$ret = 0;
        $errors = array();
		
        if(!$params['first_name']){
		    $errors[] = 'First Name is required';
        }
        if (!filter_var(trim($params['email']), FILTER_VALIDATE_EMAIL)){
		    $errors[] = 'Enter Valid email address';
        }

		if(count($errors) == 0)
		{
			
			$data = ['first_name'=>$params['first_name'],'email'=>$params['email'],'last_name'=>$params['last_name'],'is_active'=>1];
		
			$sql = " INSERT INTO  ".$this->table_name." (first_name,last_name,email,is_active)  values (:first_name, :last_name, :email,:is_active)";
			$res = $this->conn->prepare($sql);
			$res  = $res->execute($data);
		}
		if($res)
		{
			$ret_arr['success'] = true;
			$ret_arr['data'] = false;
		}
		return $ret_arr;
	
	   
   }
  
}