<?php

namespace App\Controller;

use Cake\Controller\Controller;

class TestController extends AppController {
	
	public function initialize(): void {
		parent::initialize();
		
		$this->viewBuilder()->setLayout('test');
	}
	
	public function index() {
		
	}
	
	public function add() {
		$this->RequestHandler->renderAs($this, 'json');
		
       	$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

				if (is_array($decoded)) {
					if($decoded['test'] != null) {
						$response = ['success' => 1, 'test' => 'test'];
					}
					
					$response = ['success' => 0];
				} else {
					$response = ['success' => 0];
				}

				$this->set(compact('response'));
				$this->set('_serialize', ['response']);
				$this->set('_serialize', true);
		}
                
	}
	
}

?>