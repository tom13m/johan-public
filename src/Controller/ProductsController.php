<?php

namespace App\Controller;

use Cake\Controller\Controller;

class ProductsController extends AppController {
	
	public function initialize(): void {
		parent::initialize();
		
		$this->viewBuilder()->setLayout('main');
	}
	
	/* Function for moving product stock */
	public function moveProductStock() {
		$this->RequestHandler->renderAs($this, 'json');
		
		$response = [];
		
		if ($this->request->is('post')) {
			$data = $this->request->getData('data');
			$productId = $data['product_id'];
			
			$this->loadModel('WarehousesProducts');
			
			$fromWarehouse = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['from_warehouse_id'], 'product_id' => $data['product_id']])->first();
			$toWarehouse = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['to_warehouse_id'], 'product_id' => $data['product_id']])->first();
			
			/* Patching fromWarehouse */
			if ($fromWarehouse != null) {
				$fromWarehouse->stock = $fromWarehouse->stock - $data['amount'];

				$this->WarehousesProducts->save($fromWarehouse);
			} else {
				$fromWarehouse = $this->WarehousesProducts->newEmptyEntity();
				
				$fromWarehouse->warehouse_id = $data['from_warehouse_id'];
				$fromWarehouse->product_id = $data['product_id'];
				$fromWarehouse->stock = 0 - $data['amount'];
				
				$this->WarehousesProducts->save($fromWarehouse);
			}
			
			/* Patching toWarehouse */
			if ($toWarehouse != null) {
				$toWarehouse->stock = $toWarehouse->stock + $data['amount'];

				$this->WarehousesProducts->save($toWarehouse);
			} else {
				$toWarehouse = $this->WarehousesProducts->newEmptyEntity();
				
				$toWarehouse->warehouse_id = $data['to_warehouse_id'];
				$toWarehouse->product_id = $data['product_id'];
				$toWarehouse->stock = $data['amount'];
				
				$this->WarehousesProducts->save($toWarehouse);
			}
			
			/* Receiving changed data to apply to view */
			$this->loadModel('Warehouses');
			
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
				
				unset($warehouse->products);
				
				array_push($warehousesList, ['value' => $warehouse->id, 'text' => $warehouse->name]);
			}
			
			$data = [];
				$data['warehouses'] = $warehouses;
				$data['warehousesList'] = $warehousesList;
				$data['product']['id'] = $productId;
			
			$response['data'] = $data;
		} else {
			$response['success'] = 0;
		}
		
		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}
	
	/* Function for importing products */
	public function importProducts() {
		$response = [];
		
		if($this->request->is('post')){
			$data = $this->request->getData();
			$file = $data['products_file'];
			$filename = explode('.', $file['name']);
			
			if (end($filename) == 'csv') {
				$handle = fopen($file['tmp_name'], "r");
				
				$productList = [];
				
				while($row = fgetcsv($handle)){
					$row = explode(';', implode($row));
					
					// Check if exist in database, then patch or add depending on this
					if ($product = $this->Products->findByBarcode([$row[0]])) {
						// Patch things
					} else {
						$product = $this->Products->newEmptyEntity();
						
						$product->barcode = $row[0];
						$product->name = $row[8];
						
						if ($this->Products->save($product)) {
							array_push($productList, $product->name);
							
							continue;
						} else {
							// Patch to failure list
							
							continue;
						}
					}
				}
				
				fclose($handle);
			}
			
			$response['productList'] = $productList;
		} else {
			$response['success'] = 0;
		}
		
		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}
}

?>