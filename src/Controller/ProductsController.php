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
			
			/* Write booking */
			$bookingData = array(
				'product_id' => $data['product_id'],
				'amount' => $data['amount'],
				'from_location_id' => $data['from_warehouse_id'],
				'to_location_id' => $data['to_warehouse_id']
			);

			$this->writeBooking($bookingData);

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
			
			// Finding booking reasons
			$this->loadModel('BookingReasons');

			$bookingReasonsList = $this->BookingReasons->find('list', ['keyField' => 'id', 'valueField' => 'name']);

			$data = [];
				$data['warehouses'] = $warehouses;
				$data['warehousesList'] = $warehousesList;
				$data['product']['id'] = $productId;
				$data['bookingReasonsList'] = $bookingReasonsList;

			$response['data'] = $data;
		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for correcting product stock */
	public function correctProductStock() {
		$this->RequestHandler->renderAs($this, 'json');

		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');

			/* Executing stock change */
			$this->loadModel('WarehousesProducts');

			$warehouseProduct = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['warehouse_id'], 'product_id' => $data['product_id']])->first();
			
			if ($warehouseProduct != null) {
				$warehouseProduct->stock = $warehouseProduct->stock + $data['stock'];
			} else {
				$warehouseProduct = $this->WarehousesProducts->newEmptyEntity();
				$warehouseProduct = $this->WarehousesProducts->patchEntity($warehouseProduct, $data);
			}
			
			$this->WarehousesProducts->save($warehouseProduct);
			
			/* Write booking */
			$bookingData = array(
				'product_id' => $data['product_id'],
				'amount' => $data['stock'],
				'from_location_id' => $data['warehouse_id'],
				'booking_reason_id' => $data['booking_reason_id']
			);

			$this->writeBooking($bookingData);

			/* Receiving changed data to apply to view */
			$product = $this->Products->findById($data['product_id'])->contain([
				'Suppliers' => [
					'fields' => [
						'Suppliers.name'
					]	
				]
			])->first();

			$product->totalStock = 0;

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

					$product->totalStock += $warehouse->productStock;
				} else {
					$warehouse->productStock = 0;
				}

				unset($warehouse->products);

				array_push($warehousesList, ['value' => $warehouse->id, 'text' => $warehouse->name]);
			}

			// Finding booking reasons
			$this->loadModel('BookingReasons');

			$bookingReasonsList = $this->BookingReasons->find('list', ['keyField' => 'id', 'valueField' => 'name']);

			/* Resetting data array */
			$data = [];
			$data['product'] = $product;
			$data['warehouses'] = $warehouses;
			$data['warehousesList'] = $warehousesList;
			$data['bookingReasonsList'] = $bookingReasonsList;
			$data['test'] = $bookingData;

			$response = ['data' => $data];

		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}
	
	/* Temporary function for adding a new product */
	public function addProduct() {
		$response = [];
		
		if ($this->request->is('post')) {
			$data = $this->request->getData('data');
			
			if (isset($data['barcode'])) {
				$product = $this->Products->newEmptyEntity();
				
				$product = $this->Products->patchEntity($product, $data);
				
				$data = [];
				
				if ($this->Products->save($product)) {
					$data['errorTemplate'] = 'success';
				} else {
					$data['errorTemplate'] = 'failure';
				}
			}
			
			$response['data'] = $data;
		} else {
			$data = [];
			
			$data['errorTemplate'] = 'failure';
			
			$response['data'] = $data;
		}
		
		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for importing products */
	public function importProducts() {
		if($this->request->is('post')) {
			$data = $this->request->getData('data');
			
//			$test = $data['products_file'];
			
			/* Receiving file format */
			$this->loadModel('FileFormats');
							
			$fileFormat = $this->FileFormats->findById($data['file_format_id'])->first();
			$fileFormat->format = unserialize($fileFormat->format);
			
			/* Iterating through csv rows */
			$productsFile = $data['products_file'];
			
			$productsArray = [];
			
			Foreach ($productsFile as $row) {
//				array_push($productsArray, ['name' => $row[$fileFormat->format['name']], 'barcode' => $row[$fileFormat->format['barcode']], 'description' => $row[$fileFormat->format['description']]]);
				if ($product = $this->Products->findByBarcode($row[$fileFormat->format['barcode']])->first()) {
					// Patch existing entity
					$product->name = $row[$fileFormat->format['name']];
					$product->description = $row[$fileFormat->format['description']];
					
					$this->Products->save($product);
				} else {
					// Create new entity
					if ($row[$fileFormat->format['barcode']] != '') {
						$product = $this->Products->newEmptyEntity();

						$product->barcode = $row[$fileFormat->format['barcode']];
						$product->name = $row[$fileFormat->format['name']];
						$product->description = $row[$fileFormat->format['description']];
						$product->supplier_id = $fileFormat->supplier_id;

						$this->Products->save($product);
					}
				}
			}
			
//			$data['test'] = $fileFormat->format['barcode'];
			
//			$this->Products->saveMany($products);

//			$test = $data['products_file'];

//						$file = $data['products_file'];
//						$filename = explode('.', $file['name']);
//
//						if (end($filename) == 'csv') {
//							$handle = fopen($file['tmp_name'], "r");
//							
//							/* Receiving file format */
//							$this->loadModel('FileFormats');
//							
//							$fileFormat = $this->FileFormats->findById($data['file_format_id'])->first();
//							$fileFormat->format = unserialize($fileFormat->format);
//							
//							/* Inserting or updating products */
//							$productList = [];
//							
//							while($row = fgetcsv($handle)) {
//								$row = str_replace('"', '', explode(',', implode($row)));
//								
//								// Check if barcode exists
//								if (isset($row[$fileFormat->format['barcode']])) {
//									// Check if exist in database, then patch or add depending on this
//									if ($product = $this->Products->findByBarcode($row[$fileFormat->format['barcode']])->first()) {
//										// Patch things
//										$product->name = $row[$fileFormat->format['name']];
//										$product->description = $row[$fileFormat->format['description']];
//										
//										if ($this->Products->save($product)) {
//											array_push($productList, $product->name);
//			
//											continue;
//										} else {
//											// Patch to failure list
//			
//											continue;
//										}
//									} else {
//										$product = $this->Products->newEmptyEntity();
//			
//										$product->barcode = $row[$fileFormat->format['barcode']];
//										$product->name = $row[$fileFormat->format['name']];
//										$product->description = $row[$fileFormat->format['description']];
//			
//										if ($this->Products->save($product)) {
//											array_push($productList, $product->name);
//			
//											continue;
//										} else {
//											// Patch to failure list
//			
//											continue;
//										}
//									}
//								} else {
//									
//								}
//							}
//							
//							fclose($handle);
//						}

			//			$response['productList'] = $productList;
//			$response['data'] = $data;
		} else {
			$response = [];
			
			$response['success'] = 0;
			
			$this->set(compact('response'));
			$this->viewBuilder()->setOption('serialize', true);
			$this->RequestHandler->renderAs($this, 'json');
		}
	}
}

?>