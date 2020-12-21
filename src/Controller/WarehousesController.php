<?php

namespace App\Controller;

use Cake\Controller\Controller;

class WarehousesController extends AppController {

	public function initialize(): void {
		parent::initialize();

		$this->viewBuilder()->setLayout('main');
	}
	
	public function edit() {
		$this->RequestHandler->renderAs($this, 'json');

		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');
			
			$warehouse = $this->Warehouses->findById($data['warehouse_id'])->first();
			
			$warehouse = $this->Warehouses->patchEntity($warehouse, $data);
			
			if (isset($data['product_id'])) {
				$this->loadModel('WarehousesProducts');
				
				$warehouseProduct = $this->WarehousesProducts->find()->where(['warehouse_id' => $warehouse->id, 'product_id' => $data['product_id']])->first();
				
				if (isset($data['minimum_stock'])) {
					$warehouseProduct->minimum_stock = $data['minimum_stock'];
				} elseif (isset($data['minimum_stock' . $warehouse->id])) {
					$warehouseProduct->minimum_stock = $data['minimum_stock' . $warehouse->id];
				}
				
				if (isset($data['maximum_stock'])) {
					$warehouseProduct->maximum_stock = $data['maximum_stock'];
				} elseif (isset($data['maximum_stock' . $warehouse->id])) {
					$warehouseProduct->maximum_stock = $data['maximum_stock' . $warehouse->id];
				}
				
				$this->WarehousesProducts->save($warehouseProduct);
			}
			
			$this->Warehouses->save($warehouse);
			
			/* Receiving changed data to apply to view */
			$warehouses = $this->Warehouses->find()->contain(['Products' => [
				'conditions' => [
					'Products.id' => $data['product_id']
				]
			]]);
			
			$warehousesList = [];
			
			Foreach ($warehouses as $warehouse) {
				if (count($warehouse->products) > 0) {
					$warehouse->productStock = $warehouse->products[0]->_joinData->stock;
				} else {
					$warehouse->productStock = 0;
				}
				
				$warehouse->minimumStock = $warehouse->products[0]->_joinData->minimum_stock;
				$warehouse->maximumStock = $warehouse->products[0]->_joinData->maximum_stock;

				unset($warehouse->products);
				
				array_push($warehousesList, ['value' => $warehouse->id, 'text' => $warehouse->name]);
			}
			
			// Temporary
			$product_id = $data['product_id'];
			
			$data = [];
				$data['product']['id'] = $product_id;
				$data['warehouses'] = $warehouses;
				$data['warehousesList'] = $warehousesList;
			
			$response['data'] = $data;
		} else {
			$response['success'] = false;
		}
		
		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}
	
}

?>