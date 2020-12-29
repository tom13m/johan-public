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
				
				$test = '';
				
				if ($warehouseProduct = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['warehouse_id'], 'product_id' => $data['product_id']])->first()) {
					$data['difference'] = $warehouseProduct->stock - $data['product_stock' . $warehouse->id];
					
					if ($data['difference'] < 0) {
						$data['difference'] = $data['difference'] * -1;
					}
					
					$warehouseProduct->minimum_stock = $data['minimum_stock' . $warehouse->id];
					$warehouseProduct->maximum_stock = $data['maximum_stock' . $warehouse->id];
					$warehouseProduct->stock = $data['product_stock' . $warehouse->id];
					
					$this->WarehousesProducts->save($warehouseProduct);
				} else {
					$warehouseProduct = $this->WarehousesProducts->newEmptyEntity();
					
					$warehouseProduct->warehouse_id = $data['warehouse_id'];
					$warehouseProduct->product_id = $data['product_id'];
					
					$warehouseProduct->minimum_stock = $data['minimum_stock' . $warehouse->id];
					$warehouseProduct->maximum_stock = $data['maximum_stock' . $warehouse->id];
					$warehouseProduct->stock = $data['product_stock' . $warehouse->id];
					
					$this->WarehousesProducts->save($warehouseProduct);
					
					$data['difference'] = $data['product_stock' . $warehouse->id];
				}
			}
			
			$this->Warehouses->save($warehouse);
			
			/* Write booking */
			$bookingData = array(
				'product_id' => $data['product_id'],
				'amount' => $data['difference'],
				'from_location_id' => $data['warehouse_id']
			);

			$this->writeBooking($bookingData);
			
			/* Receiving changed data to apply to view */
			$this->loadmodel('Products');
			
			$product = $this->Products->findById($data['product_id'])->contain([
				'Suppliers' => [
					'fields' => [
						'Suppliers.name'
					]	
				]
			])->first();

			$product->totalStock = 0;
			
			$warehouses = $this->Warehouses->find()->contain(['Products' => [
				'conditions' => [
					'Products.id' => $data['product_id']
				]
			]]);
			
			$warehousesList = [];
			
			Foreach ($warehouses as $warehouse) {
				if ($warehouse->products != null) {
					$warehouse->productStock = $warehouse->products[0]->_joinData->stock;
					
					$product->totalStock += $warehouse->productStock;
				} else {
					$warehouse->productStock = 0;
				}
				
				$warehouse->minimumStock = $warehouse->products[0]->_joinData->minimum_stock;
				$warehouse->maximumStock = $warehouse->products[0]->_joinData->maximum_stock;

				unset($warehouse->products);
				
				array_push($warehousesList, ['value' => $warehouse->id, 'text' => $warehouse->name]);
			}
			
			
			$data = [];
				$data['product'] = $product;
				$data['warehouses'] = $warehouses;
				$data['warehousesList'] = $warehousesList;
				$data['test'] = $test;
			
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