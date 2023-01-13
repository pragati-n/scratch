<?php

// include ROOT_PATH.'/app/db.php';
require ROOT_PATH.'/app/model/scratch.php';
// include ROOT_PATH.'/config/db.php';




class scratchcontroller
{
	
	public function __construct()
	{
		
		$this->model = new scratch();
	}
	
	public function generate_scratch_card($params)
	{
		
		// echo "<pre>";
		// print_r($params);
		return $this->model->generate_scratch_cards($params);
	}
	
	
	
}
