<?php

// include ROOT_PATH.'/app/db.php';
require ROOT_PATH.'/app/model/user.php';
// include ROOT_PATH.'/config/db.php';




class usercontroller
{
	
	public function __construct()
	{
		
		$this->model = new user();
	}
	
	public function fetch_data()
	{
		
		return $this->model->fetch_user_data();
	}
	
	
	
}
