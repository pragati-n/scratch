<?php

require ROOT_PATH.'/app/controllers/usercontroller.php';
require ROOT_PATH.'/app/controllers/scratchcontroller.php';
// require ROOT_PATH.'/app/controllers/transactioncontroller.php';
// include ROOT_PATH.'/app/db.php';
// include ROOT_PATH.'/config/db.php';

class server{
	public $path = array(
				 '/get_user' => [
					'GET' => 'usercontroller@fetch_data',
				],
				'/update_user' => [
					'PUT' => 'usercontroller@update_user',
				],
				'/create_user' => [
					 'POST' => 'usercontroller@create_user',
				],
				'/delete_user' => [
					 'DELETE' => 'usercontroller@delete_user',
				],
				'/generate_scratch_card' => [
					 'POST' => 'scratchcontroller@generate_scratch_card',
				],
				'/unused_scratch_card' => [
					 'GET' => 'scratchcontroller@unused_scratch_card',
				],
	
	);
    public function handle($route)
    {
        try {
            $routeData = $this->check_path($route);
			$response = $this->loadController($routeData);
			  http_response_code($response['code']);
            echo json_encode([
                'success' => $response['success'] ?? true,
				'code' =>$response['code'],
                 'data' => $response['data'],
				 
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }  
    }

    public function check_path($route){
		
        if($this->path[$route])
		{
            return $this->path[$route];
        }
        else{
            
            throw new \Exception("Not valid path", 404);
            
        }
    }

   

   public function loadController($routeData){
        $type = $_SERVER['REQUEST_METHOD'];
		$udata = json_decode(file_get_contents("php://input"),true);
		// echo 'udata';var_dump($udata);
		$params = $udata;
		
        if(isset($routeData[$type])){
            $routeParams = explode("@", $routeData[$type]);
            $className = $routeParams[0];
            $controller = new $className();
			
			// var_dump('POST');
			// var_dump($_POST);
            return $controller->{$routeParams[1]}($params);
        }
        else{
            throw new \Exception("Route not found", 404);
        }
    } 
}