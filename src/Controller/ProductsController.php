<?php

namespace App\Controller;

use Cake\Controller\Controller;
//use SimpleXLSX;
//use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
//use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ProductsController extends AppController {

	public function initialize(): void {
		parent::initialize();

		$this->viewBuilder()->setLayout('main');
	}

	/* Function for receiving general product information */
	public function getProductData() {
		if ($this->request->is('post')) {
			$response = [];

			$data = $this->request->getData('data');

			if ($data['barcode'] != false) {
				$product = $this->Products->findByBarcode($data['barcode'])->first();
			} elseif ($data['product_id'] != false) {
				$product = $this->Products->findById($data['product_id'])->first();
			}

			/* Creating booking reasons list */
			$this->loadModel('BookingReasons');

			$bookingReasonsList = $this->BookingReasons->find('list', ['keyField' => 'id', 'valueField' => 'name']);

			$data = [];
			$data['product'] = $product;
			$data['bookingReasonsList'] = $bookingReasonsList;

			$response['data'] = $data;

			$this->set(compact('response'));
			$this->viewBuilder()->setOption('serialize', true);
			$this->RequestHandler->renderAs($this, 'json');
		}
	}

	/* Function for moving a single product stock */
	public function moveProductStock() {
		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');
			$productId = $data['product_id'];

			$this->loadModel('WarehousesProducts');
			$this->loadModel('Warehouses');

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
				'from_location_id' => $this->Warehouses->findById($data['from_warehouse_id'])->first()->location_id,
				'to_location_id' => $this->Warehouses->findById($data['to_warehouse_id'])->first()->location_id,
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

	/* Function for moving multiple products stocks */
	public function moveProductsStocks() {
		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');

			/* Executing stock movement */
			$this->loadModel('WarehousesProducts');
			$this->loadModel('Warehouses');

			$productCount = count($data['products']);

			for ($i = 0; $i < $productCount; $i++) {
				$fromWarehouse = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['from_warehouse_id'], 'product_id' => $data['products'][$i]])->first();
				$toWarehouse = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['to_warehouse_id'], 'product_id' => $data['products'][$i]])->first();

				/* Patching fromWarehouse */
				if ($fromWarehouse != null) {
					$fromWarehouse->stock = $fromWarehouse->stock - $data['amounts'][$i];

					$this->WarehousesProducts->save($fromWarehouse);
				} else {
					$fromWarehouse = $this->WarehousesProducts->newEmptyEntity();

					$fromWarehouse->warehouse_id = $data['from_warehouse_id'];
					$fromWarehouse->product_id = $data['products'][$i];
					$fromWarehouse->stock = 0 - $data['amounts'][$i];

					$this->WarehousesProducts->save($fromWarehouse);
				}

				/* Patching toWarehouse */
				if ($toWarehouse != null) {
					$toWarehouse->stock = $toWarehouse->stock + $data['amounts'][$i];

					$this->WarehousesProducts->save($toWarehouse);
				} else {
					$toWarehouse = $this->WarehousesProducts->newEmptyEntity();

					$toWarehouse->warehouse_id = $data['to_warehouse_id'];
					$toWarehouse->product_id = $data['products'][$i];
					$toWarehouse->stock = $data['amounts'][$i];

					$this->WarehousesProducts->save($toWarehouse);
				}

				/* Write booking */
				$bookingData = array(
					'product_id' => $data['products'][$i],
					'amount' => $data['amounts'][$i],
					'from_location_id' => $this->Warehouses->findById($data['from_warehouse_id'])->first()->location_id,
					'to_location_id' => $this->Warehouses->findById($data['to_warehouse_id'])->first()->location_id
				);

				$this->writeBooking($bookingData);
			}

			$response['data'] = $data;
			$response['success'] = 1;
		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for correcting a single product stock */
	public function correctProductStock() {
		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');

			/* Executing stock change */
			$this->loadModel('WarehousesProducts');
			$this->loadModel('Warehouses');
			$this->loadModel('BookingReasons');

			$warehouseProduct = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['warehouse_id'], 'product_id' => $data['product_id']])->first();
			$bookingReason = $this->BookingReasons->findById($data['booking_reason_id'])->first();

			if ($bookingReason->state == 'negative') {
				$data['stock'] = $data['stock'] * -1;
			}

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
				'from_location_id' => $this->Warehouses->findById($data['warehouse_id'])->first()->location_id,
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

			$response['data'] = $data;

		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for correcting multiple products stocks */
	public function correctProductsStocks() {
		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');

			/* Executing stock change */
			$this->loadModel('WarehousesProducts');
			$this->loadModel('Warehouses');
			$this->loadModel('BookingReasons');

			$productCount = count($data['products']);

			for ($i = 0; $i < $productCount; $i++) {
				$warehouseProduct = $this->WarehousesProducts->find()->where(['warehouse_id' => $data['warehouse_id'], 'product_id' => $data['products'][$i]])->first();
				$bookingReason = $this->BookingReasons->findById($data['bookingReasons'][$i])->first();

				if ($bookingReason->state == 'negative') {
					$data['amounts'][$i] = $data['amounts'][$i] * -1;
				}

				if ($warehouseProduct != null) {
					$warehouseProduct->stock = $warehouseProduct->stock + $data['amounts'][$i];
				} else {
					$warehouseProduct = $this->WarehousesProducts->newEmptyEntity();

					$warehouseProduct['warehouse_id'] = $data['warehouse_id'];
					$warehouseProduct['product_id'] = $data['products'][$i];
					$warehouseProduct['stock'] = $data['amounts'][$i];
				}

				$this->WarehousesProducts->save($warehouseProduct);

				/* Write booking */
				$bookingData = array(
					'product_id' => $data['products'][$i],
					'amount' => $data['amounts'][$i],
					'from_location_id' => $this->Warehouses->findById($data['warehouse_id'])->first()->location_id,
					'booking_reason_id' => $data['bookingReasons'][$i]
				);

				$this->writeBooking($bookingData);
			}

			$response['data'] = $data;
			$response['success'] = 1;
		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for saving a temporary product set */
	public function saveProductsSet() {
		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');


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
		$response = [];

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

			for ($i = 0; i < count($productsFile); $i++) {
				//				array_push($productsArray, ['name' => $row[$fileFormat->format['name']], 'barcode' => $row[$fileFormat->format['barcode']], 'description' => $row[$fileFormat->format['description']]]);
				if ($product = $this->Products->findByBarcode($productsFile[$i][$fileFormat->format['barcode']])->first()) {
					// Patch existing entity
					if (isset($productsFile[$i][$fileFormat->format['name']])) {
						$product->name = $productsFile[$i][$fileFormat->format['name']];
					} else {
						$product->name = '...';
					}

					if (isset($productsFile[$i][$fileFormat->format['description']])) {
						$product->description = $productsFile[$i][$fileFormat->format['description']];
					} else {
						$product->description = '...';
					}

					$this->Products->save($product);
				} else {
					// Create new entity
					if ($productsFile[$i][$fileFormat->format['barcode']] != '') {
						$product = $this->Products->newEmptyEntity();

						$product->barcode = $productsFile[$i][$fileFormat->format['barcode']];

						if (isset($productsFile[$i][$fileFormat->format['name']])) {
							$product->name = $productsFile[$i][$fileFormat->format['name']];
						} else {
							$product->name = '...';
						}

						if (isset($productsFile[$i][$fileFormat->format['description']])) {
							$product->description = $productsFile[$i][$fileFormat->format['description']];
						} else {
							$product->description = '...';
						}

						$product->supplier_id = $fileFormat->supplier_id;

						$this->Products->save($product);
					}
				}
			}

			$response['success'] = 1;

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
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for handling updated product data */
	public function updateProducts() {
		/* Find file format */
		$this->loadModel('FileFormats');

		$fileFormat = $this->FileFormats->findById('6')->contain(['Suppliers'])->first();
		$fileFormat->format = unserialize($fileFormat->format);

		/* Finding existing products by supplier and producing an array */
		$products = $this->Products->findBySupplierId($fileFormat->supplier_id)->select(['barcode']);
		$productsArray = [];

		Foreach ($products as $product) {
			array_push($productsArray, $product->barcode);
		}

		/* Reading file */
		if ($fileFormat->file_extension == '.csv') {
			/*$filePath = WWW_ROOT .'product_data'. DS . $fileFormat->supplier['name'] . '.csv';*/
			$filePath = WWW_ROOT .'product_data'. DS . 'minMaxOS.csv';
			$file = fopen($filePath, "r");

			/* Cheking if product is in csv, if so update and remove from productsarray */		
			while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
				if (count($row) == 1) {
//					$row = str_replace('"', '', $row[0]);
					
//					debug($row);
//					$row[0] = str_replace('"', '', explode(',', implode($row[0])));
					
					$row = str_getcsv($row[0], ",", '"', '"');
					
//					debug($row);
				}

				$row[$fileFormat->format['barcode']] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[$fileFormat->format['barcode']]);

//				debug($row);
				
				if (in_array($row[$fileFormat->format['barcode']], $productsArray)) {
					/* Update product entity */
					$product = $this->Products->findByBarcode($row[$fileFormat->format['barcode']])->first();

					$product->name = utf8_encode($row[$fileFormat->format['name']]);
					$product->description = utf8_encode($row[$fileFormat->format['description']]);
					$product->supplier_id = $fileFormat->supplier->id;

					if ($product2 = $this->Products->save($product)) {
						$key = array_search($product->barcode, $productsArray);

						unset($productsArray[$key]);

						/* Temporary stock manipulation */
						$this->loadModel('WarehousesProducts');

						if ($warehouseProduct = $this->WarehousesProducts->findByProductId($product2->id)->first()) {
							$warehouseProduct->warehouse_id = 1;
							$warehouseProduct->product_id = $product2->id;
							$warehouseProduct->stock = intval($row[4]);
							$warehouseProduct->minimum_stock = intval($row[2]);
							$warehouseProduct->maximum_stock = intval($row[3]);

							$this->WarehousesProducts->save($warehouseProduct);
						} else {
							$warehouseProduct = $this->WarehousesProducts->newEmptyEntity();

							$warehouseProduct->warehouse_id = 1;
							$warehouseProduct->product_id = $product2->id;
							$warehouseProduct->stock = intval($row[4]);
							$warehouseProduct->minimum_stock = intval($row[2]);
							$warehouseProduct->maximum_stock = intval($row[3]);

							$this->WarehousesProducts->save($warehouseProduct);
						}
					} else {
						continue;
					}
				} else {
					/* Create new product entity */
					$product = $this->Products->newEmptyEntity();

					$product->barcode = $row[$fileFormat->format['barcode']];
					$product->name = utf8_encode($row[$fileFormat->format['name']]);
					$product->description = utf8_encode($row[$fileFormat->format['description']]);
					$product->supplier_id = $fileFormat->supplier->id;

					$product2 = $this->Products->save($product);

					/* Temporary stock manipulation */
					$this->loadModel('WarehousesProducts');

					$warehouseProduct = $this->WarehousesProducts->newEmptyEntity();

					$warehouseProduct->warehouse_id = 1;
					$warehouseProduct->product_id = $product2->id;
					$warehouseProduct->stock = intval($row[4]);
					$warehouseProduct->minimum_stock = intval($row[2]);
					$warehouseProduct->maximum_stock = intval($row[3]);

					$this->WarehousesProducts->save($warehouseProduct);
				}
			}

			fclose($file);
			//		} elseif ($fileFormat->file_extension == '.xlsx') {
			//			$filePath = WWW_ROOT .'product_data'. DS . $fileFormat->supplier['name'] . '_test.xlsx';
			//
			//			$reader = ReaderEntityFactory::createXLSXReader();
			//
			//			$reader->open($filePath);
			//
			//			foreach ($reader->getSheetIterator() as $sheet) {
			//				foreach ($sheet->getRowIterator() as $xlsxRow) {
			//					// do stuff with the row
			//					$row = $xlsxRow->getCells();
			//					
			//					debug($row);

			//					$standardLength = 0;
			//					$barcodeLength = floor(log10(intval($row[$fileFormat->format['barcode']]->getValue())) + 1);
			//
			//					switch ($fileFormat->supplier->name) {
			//						case 'Oosterberg': 
			//							$standardLength = 8;
			//					}
			//
			//					if ($barcodeLength < $standardLength) {
			//						$difference = $standardLength - $barcodeLength;
			//
			//						$row[$fileFormat->format['barcode']] = strval($row[$fileFormat->format['barcode']]);
			//
			//						for ($i = 0; $i < $difference; $i++) {
			//							$row[$fileFormat->format['barcode']] = '0' . $row[$fileFormat->format['barcode']];
			//						}
			//					}
			//
			//					if (in_array($row[$fileFormat->format['barcode']], $productsArray)) {
			//						/* Update product entity */
			//						$product = $this->Products->findByBarcode($row[$fileFormat->format['barcode']])->first();
			//
			//						$product->name = $row[$fileFormat->format['name']];
			//						$product->description = $row[$fileFormat->format['description']];
			//
			//						if ($this->Products->save($product)) {
			//							$key = array_search($product->barcode, $productsArray);
			//
			//							unset($productsArray[$key]);
			//						} else {
			//							continue;
			//						}
			//					} else {
			//						continue;
			//					}
			//				}
			//			}

			//			$reader->close();

			//			$xlsx = SimpleXLSX::parse($filePath);

			//			Foreach (array_slice($xlsx->rows(), 1) as $row) {
			//				$standardLength = 0;
			//				$barcodeLength = floor(log10($row[$fileFormat->format['barcode']]) + 1);
			//
			//				switch ($fileFormat->supplier->name) {
			//					case 'Oosterberg': 
			//						$standardLength = 8;
			//				}
			//
			//				if ($barcodeLength < $standardLength) {
			//					$difference = $standardLength - $barcodeLength;
			//					
			//					$row[$fileFormat->format['barcode']] = strval($row[$fileFormat->format['barcode']]);
			//					
			//					for ($i = 0; $i < $difference; $i++) {
			//						$row[$fileFormat->format['barcode']] = '0' . $row[$fileFormat->format['barcode']];
			//					}
			//				}
			//
			//				if (in_array($row[$fileFormat->format['barcode']], $productsArray)) {
			//					/* Update product entity */
			//					$product = $this->Products->findByBarcode($row[$fileFormat->format['barcode']])->first();
			//
			//					$product->name = $row[$fileFormat->format['name']];
			//					$product->description = $row[$fileFormat->format['description']];
			//
			//					if ($this->Products->save($product)) {
			//						$key = array_search($product->barcode, $productsArray);
			//
			//						unset($productsArray[$key]);
			//					} else {
			//						continue;
			//					}
			//				} else {
			//					continue;
			//				}
			//			}


			//
			//			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			//
			//			$spreadsheet = $reader->load($filePath);
			//			$worksheet = $spreadsheet->getActiveSheet();
			//
			//			$i = 1;
			//			
			//			$array = $worksheet->toArray(null, true, true, true);

			//			Foreach ($worksheet->getRowIterator() as $row) {
			//				$cellValue = $worksheet->getCellByColumnAndRow(1, $i)->getValue();
			//				
			//				debug($cellValue);
			//				
			//				break;
			////				Foreach ($row->getCellIterator() as $cell) {
			////					if (in_array($row[], $productsArray)) {
			////						
			////					}
			////				}	
			//				
			//				$i++;
			//			}
		}
	}

	public function test() {
		/* Find file format */
		$this->loadModel('FileFormats');

		$fileFormat = $this->FileFormats->findById('1')->contain(['Suppliers'])->first();
		$fileFormat->format = unserialize($fileFormat->format);

		/* Finding existing products by supplier and producing an array */
		$products = $this->Products->findBySupplierId($fileFormat->supplier_id)->select(['barcode']);
		$productsArray = [];

		Foreach ($products as $product) {
			array_push($productsArray, $product->barcode);
		}

		/* Reading file */
		if ($fileFormat->file_extension == '.csv') {
			/*$filePath = WWW_ROOT .'product_data'. DS . $fileFormat->supplier['name'] . '.csv';*/
			$filePath = WWW_ROOT .'product_data'. DS . 'telp.csv';
			$file = fopen($filePath, "r");

			/* Cheking if product is in csv, if so update and remove from productsarray */
			while (($row = fgetcsv($file)) !== FALSE) {
				if (!(count($row) > 1)) {
					$row = str_replace('"', '', explode(',', implode($row)));
				}

				$row[$fileFormat->format['barcode']] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[$fileFormat->format['barcode']]);

				if (in_array($row[$fileFormat->format['barcode']], $productsArray)) {

				} else {
					echo $row[$fileFormat->format['barcode']];
				}
			}
		}
	}
}

?>