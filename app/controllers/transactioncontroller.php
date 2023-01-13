<?php

// include ROOT_PATH.'/app/db.php';
require ROOT_PATH.'/app/model/transaction.php';
// include ROOT_PATH.'/config/db.php';




class transactioncontroller

{
	
	public function __construct()
	{
		
		$this->model = new transaction();
	}
	
	public function assign_transaction($params)
	{
		
		return $this->model->assign_transactions($params);
	}
	
	
	
}
