<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;

/* Configuration for pdf generator */
Configure::write('CakePdf', [
	'engine' => 'CakePdf.dompdf',
	'margin' => [
		'bottom' => 15,
		'left' => 50,
		'right' => 30,
		'top' => 45
	],
	'orientation' => 'landscape',
	'download' => false
]);

class OrdersController extends AppController {

	public function initialize(): void {
		parent::initialize();

		$this->viewBuilder()->setLayout('main');
	}

	/* Function for receiving order data */
	public function getOrderData() {
		$response = [];

		if ($this->request->is('post')) {
			/* Receiving data */
			$data = $this->request->getData('data');

			$order = $this->Orders->findById($data['orderId'])->contain(['Suppliers'])->first();
			$order->products = unserialize($order->products);

			if ($order->products == null) {
				$order->products = '';
			} else {
				$this->loadModel('Products');
				$this->loadModel('Warehouses');
				$this->loadModel('WarehousesProducts');

				$orderProducts = $order->products;
				$order->products = [];
				$orderWarehouse = $this->Warehouses->find()->where(['order_warehouse' => 1])->first();

				Foreach($orderProducts as $orderProduct) {
					$product = $this->Products->findByBarcode($orderProduct['barcode'])->contain(['Suppliers'])->first();

					$warehouseProduct = $this->WarehousesProducts->find()->where(['product_id' => $product->id, 'warehouse_id' => $orderWarehouse->id])->first();

					/* Stock */
					$orderWarehouseProducts = $this->WarehousesProducts->find()->where(['product_id' => $product->id]);

					$product->stock = 0;

					Foreach($orderWarehouseProducts as $orderWarehouseProduct) {
						$product->stock += $orderWarehouseProduct->stock;
					}

					/* Amount */
					$product->amount = $orderProduct['amount'];

					/* Min/Max */
					if ($warehouseProduct != null) {
						$product->minMax = $warehouseProduct->minimum_stock . '/' . $warehouseProduct->maximum_stock;
					} else {
						$product->minMax = '0/0';
					}

					/* Order */
					$product->orderId = $order->id;

					array_push($order->products, $product);
				}
			}
			
			/* Preparing list of customers */
			$this->loadModel('Customers');
			
			$customersList = $this->Customers->find('list', ['keyField' => 'id', 'valueField' => 'name']);

			/* Resetting data array */
			$data = [];
			$data['order'] = $order;
			$data['customersList'] = $customersList;

			$response['data'] = $data;
		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for adding a new order */
	public function addOrder() {
		$response = [];

		if ($this->request->is('post')) {
			$data = $this->request->getData('data');

			/* Creating new order */
			$order = $this->Orders->newEmptyEntity();

			$latestOrder = $this->Orders->find()->order(['order_no' => 'DESC'])->first();

			/* Order name */
			if ($data['name'] != '') {
				$order->name = $data['name'];
			} else {
				if ($latestOrder != null) {
					$order->name = 'Bestelling #' . ($latestOrder->id + 1);
				} else {
					$order->name = 'Bestelling #1';
				}
			}

			/* Order no */
			if ($latestOrder != null) {
				$order->order_no = $latestOrder->id + 1;
			} else {
				$order->order_no = 1;
			}

			/* Supplier */
			$order->supplier_id = $data['supplier_id'];

			/* Checking if new order needs to auto filled */
			if ($data['autoFill'] == true) {
				/* Add a prefilled order list by checking product stocks */
				$this->loadModel('Products');
				$this->loadModel('Warehouses');
				$this->loadModel('WarehousesProducts');

				$products = $this->Products->find()->contain(['Warehouses'])->where(['Products.supplier_id' => $order->supplier_id]);
				$orderWarehouse = $this->Warehouses->find()->where(['order_warehouse' => 1])->first();
				$productsArray = [];

				Foreach($products as $product) {
					$product->stock = 0;

					Foreach ($product->warehouses as $productWarehouse) {
						$product->stock += $productWarehouse->_joinData->stock;
					}

					/* Simple check if product needs to be restocked -> to be optimized */
					if ($orderWarehouseProduct = $this->WarehousesProducts->find()->where(['product_id' => $product->id, 'warehouse_id' => $orderWarehouse->id])->first()) {
						if ($product->stock <= $orderWarehouseProduct->minimum_stock) {
							$wantedAmount = $orderWarehouseProduct->maximum_stock - $product->stock;

							array_push($productsArray, ['barcode' => $product->barcode, 'amount' => $wantedAmount]);
						}
					}
				}

				$order->products = serialize($productsArray);
			} else {
				/* Add a empty order */
				$order->products = serialize(array());
			}

			/* Saving new order */
			$s_order = $this->Orders->save($order);

			/* Resetting data array */
			$data = [];
			$data['order'] = $s_order;

			$response['data'] = $data;
		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for adding a product to an order */
	public function addProductToOrder() {
		$response = [];

		if ($this->request->is('post')) {
			/* Receiving data */
			$data = $this->request->getData('data');

			/* Check if product exists */
			$this->loadModel('Products');

			if ($orderProduct = $this->Products->findByBarcode($data['barcode'])->contain(['Suppliers'])->first()) {
				/* Preparing to add product to products array of order */
				$order = $this->Orders->findById($data['orderId'])->first();

				/* Check if product is from order with chosen supplier */
				if ($order->supplier_id == $orderProduct->supplier->id) {
					$productsArray = unserialize($order->products);

					/* Check if product is already present in order */
					$productPresent = false;

					Foreach($productsArray as $arrayProduct) {
						if ($orderProduct->barcode == $arrayProduct['barcode']) {
							$productPresent = true;
							break;
						}
					}

					if ($productPresent == false) {
						/* Add product to order */
						array_push($productsArray, ['barcode' => $data['barcode'], 'amount' => 0]);

						$order->products = serialize($productsArray);

						if ($order = $this->Orders->save($order)) {
							$response['success'] = 1;

							/* Setting order product details */
							$this->loadModel('Warehouses');
							$this->loadModel('WarehousesProducts');

							$orderWarehouse = $this->Warehouses->find()->where(['order_warehouse' => 1])->first();
							$warehouseProduct = $this->WarehousesProducts->find()->where(['product_id' => $orderProduct->id, 'warehouse_id' => $orderWarehouse->id])->first();

							/* Stock */
							$orderWarehouseProducts = $this->WarehousesProducts->find()->where(['product_id' => $orderProduct->id]);

							$orderProduct->stock = 0;

							Foreach($orderWarehouseProducts as $orderWarehouseProduct) {
								$orderProduct->stock += $orderWarehouseProduct->stock;
							}

							/* Amount */
							$orderProduct->amount = 0;

							/* Min/Max */
							if ($warehouseProduct != null) {
								$orderProduct->minMax = $warehouseProduct->minimum_stock . '/' . $warehouseProduct->maximum_stock;
							} else {
								$orderProduct->minMax = '0/0';
							}

							/* Order id */
							$orderProduct->orderId = $order->id;

							/* Check if it is the first product added */
							if (count($productsArray) == 1) {
								$orderProduct->first = true;
							} else {
								$orderProduct->first = false;
							}

							/* Resetting data array */
							$data = [];
							$data['orderProduct'] = $orderProduct;

							$response['data'] = $data;
						} else {
							$response['success'] = 0;
						}
					} else {
						/* Remind user that the product is already in the order */
						$response['success'] = 2;

						$orderProduct->orderId = $order->id;

						$data = [];
						$data['orderProduct'] = $orderProduct;

						$response['data'] = $data;
					}
				} else {
					/* Product does not belong to supplier */
					$data['errorTemplate'] = 'falseBarcodeSupplier';
					
					$response['data'] = $data;
					$response['success'] = 0;
				}
			} else {
				/* Product not found */
				$data['errorTemplate'] = 'falseBarcode';
					
				$response['data'] = $data;
				$response['success'] = 0;
			}
		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Function for saving an order */
	public function saveOrder() {
		$response = [];

		if ($this->request->is('post')) {
			/* Receiving data */
			$data = $this->request->getData('data');

			/* Finding order */
			$order = $this->Orders->findById($data['order_id'])->first();

			/* Refactoring products array */
			$productsArray = [];
			$barcodeArray = [];
			$order->products = unserialize($order->products);

			for ($i = 0; $i < count($data['barcode']); $i++) {
				array_push($productsArray, ['barcode' => $data['barcode'][$i], 'amount' => $data['amount'][$i]]);
				array_push($barcodeArray, $data['barcode'][$i]);
			}

			$order->products = $productsArray;
			$s_order = clone $order;
			$s_order->products = serialize($productsArray);

			/* Saving order */
			if ($this->Orders->save($s_order)) {
				/* Resetting data array */
				$data = [];
				$data['order'] = $order;
				$data['barcodeArray'] = $barcodeArray;

				$response['data'] = $data;
				$response['success'] = 1;
			} else {
				$response['success'] = 0;
			}
		} else {
			$response['success'] = 0;
		}

		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

	/* Test function for pdf */
	public function test() {
		//		$this->viewBuilder()->setOption(
		//            'pdfConfig',
		//            [
		//                'orientation' => 'portrait',
		//				'filename' => APP . 'element'. DS . 'coreSections' . DS . 'orderCoreSection' . DS . 'pdf' . DS . 'order.php',
		//                'filename' => 'Test'
		//            ]
		//        );
		$this->viewBuilder()->setClassName('CakePdf.Pdf');

		$this->viewBuilder()->setOption(
			'pdfConfig',
			[
				'download' => false, // This can be omitted if "filename" is specified.
				'filename' => 'Test.pdf' // This can be omitted if you want file name based on URL.
			]
		);

		$this->render('/Element/coreSections/orderCoreSection/pdf/order');
	}

}

?>