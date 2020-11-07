<?php

namespace App\Controller;

use Cake\Controller\Controller;

class MainController extends AppController {
	
	public function initialize(): void {
		parent::initialize();
		
		if ($this->request->is('mobile')) {
			$this->viewBuilder()->setLayout('main_mobile');
		} else {
			$this->viewBuilder()->setLayout('main');
		}
	}
	
	/* Function for rendering an element */
	public function renderElement($element) {
		$this->RequestHandler->renderAs($this, 'json');
		
		if ($this->request->is('post')) {
            /* Receiving possible data and sending it to the view */
			if (!empty($this->request->getData('data'))) {
                $data = $this->request->getData('data');
				
//				debug($data);
				
				$this->set(compact('data'));
            }
		}
		 
		$element = str_replace('_', '/', $element);
		
		$this->viewBuilder()->setLayout('ajax');
		
		$this->render('/Element/' . $element);
	}
	
	/* Index function */
	public function index() {
		
	}
	
	/* Main function scan core section */
	public function getProductData() {
		if ($this->request->is('post')) {
			$data = $this->request->getData('data');
			
			// Finding product
			$barcode = $data['barcode'];
			
			$this->loadModel('Products');
			
			$product = $this->Products->findByBarcode($barcode)->first();
			
			// Finding warehouses
			$this->loadModel('Warehouses');
			
			$warehouses = $this->Warehouses->find()->contain(['Products' => [
				'conditions' => [
					'Products.id' => $product->id
				]
			]]);
			
			$warehousesList = [];

			Foreach ($warehouses as $warehouse) {
				if (count($warehouse->products) > 0) {
					$warehouse->productStock = $warehouse->products[0]->_joinData->stock;
				} else {
					$warehouse->productStock = 0;
				}
				
				unset($warehouse->products);
				
				array_push($warehousesList, ['value' => $warehouse->id, 'text' => $warehouse->name]);
			}
			
			/* Allowed actions for action menu */
			$allowedActions = ['stockMovement' => 'Voorraadverplaatsing', 'test' => 'test'];
			
			/* Reseting data array */
			$data = [];
				$data['product'] = $product;
				$data['warehouses'] = $warehouses;
				$data['warehousesList'] = $warehousesList;
				$data['allowedActions'] = $allowedActions;
			
			$response = ['data' => $data];
			
			$this->set(compact('response'));
			$this->viewBuilder()->setOption('serialize', true);
			$this->RequestHandler->renderAs($this, 'json');
		}
	}
	
}

?>