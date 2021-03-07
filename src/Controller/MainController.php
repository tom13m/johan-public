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
	public function initScanCoreSection() {
		if ($this->request->is('post')) {
			$data = $this->request->getData('data');

			if ($data != null) {
				// Finding product
				$barcode = $data['barcode'];

				$this->loadModel('Products');

				if ($this->Products->findByBarcode($barcode)->first() != null) {
					$product = $this->Products->findByBarcode($barcode)->contain([
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

							$warehouse->minimumStock = $warehouse->products[0]->_joinData->minimum_stock;
							$warehouse->maximumStock = $warehouse->products[0]->_joinData->maximum_stock;
						} else {
							$warehouse->productStock = 0;

							$warehouse->minimumStock = 0;
							$warehouse->maximumStock = 0;
						}

						unset($warehouse->products);

						array_push($warehousesList, ['value' => $warehouse->id, 'text' => $warehouse->name]);
					}

					// Finding booking reasons
					$this->loadModel('BookingReasons');

					$bookingReasonsList = $this->BookingReasons->find('list', ['keyField' => 'id', 'valueField' => 'name']);

					/* Allowed actions for action menu */
					$allowedActions = [
						'editWarehouse' => 'Wijzig magazijn',
						'stockMovement' => 'Voorraadverplaatsing',
						'stockCorrection' => 'Voorraadcorrectie'
					];

					/* Temporary */
					$this->loadModel('Suppliers');

					$suppliersList = $this->Suppliers->find('list', ['keyField' => 'id', 'valueField' => 'name']);

					/* Resetting data array */
					$data = [];
					$data['product'] = $product;
					$data['warehouses'] = $warehouses;
					$data['warehousesList'] = $warehousesList;
					$data['bookingReasonsList'] = $bookingReasonsList;
					$data['allowedActions'] = $allowedActions;
					$data['suppliersList'] = $suppliersList;

					$response = ['data' => $data];
				} else {
					$response['success'] = 0;
					$response['errorTemplate'] = 'falseBarcode';
				}
			} else {
				$response['success'] = 1;
			}

			$this->set(compact('response'));
			$this->viewBuilder()->setOption('serialize', true);
			$this->RequestHandler->renderAs($this, 'json');
		}
	}

	/* Main function products core section */
	public function initProductsCoreSection() {
		if ($this->request->is('post')) {
			$this->loadModel('Products');

			/* Retrieving products */
			$products = $this->Products->find('all', [
				'limit' => 10
			]);

			/* Creating a list with the file formats */
			$this->loadModel('FileFormats');

			$fileFormats = $this->FileFormats->find()->where(['FileFormats.supplier_id IS NOT' => null])->contain(['Suppliers']);
			$fileFormatsList = [];

			Foreach ($fileFormats as $fileFormat) {
				array_push($fileFormatsList, ['value' => $fileFormat->id, 'text' => $fileFormat->supplier->name]);
			}

			/* Creating a list with the suppliers */
			$this->loadModel('Suppliers');

			$suppliersList = $this->Suppliers->find('list', ['keyField' => 'id', 'valueField' => 'name']);

			/* Resetting data array */
			$data = [];
			$data['products'] = $products;
			$data['fileFormatsList'] = $fileFormatsList;
			$data['suppliersList'] = $suppliersList;

			$response = ['data' => $data];

			$this->set(compact('response'));
			$this->viewBuilder()->setOption('serialize', true);
			$this->RequestHandler->renderAs($this, 'json');
		}
	}

	/* Main function stocktaking core section */
	public function initStocktakingCoreSection() {
		if ($this->request->is('post')) {
			/* Creating the warehouses list */
			$this->loadModel('Warehouses');

			$warehousesList = $this->Warehouses->find('list', ['keyField' => 'id', 'valueField' => 'name']);

			/* Resetting data array */
			$data = [];
			$data['warehousesList'] = $warehousesList;

			$response = ['data' => $data];

			$this->set(compact('response'));
			$this->viewBuilder()->setOption('serialize', true);
			$this->RequestHandler->renderAs($this, 'json');
		}
	}

	/* Main function order core section */
	public function initOrderCoreSection() {
		if ($this->request->is('post')) {
			

			/* Resetting data array */
			$data = [];

			$response = ['data' => $data];

			$this->set(compact('response'));
			$this->viewBuilder()->setOption('serialize', true);
			$this->RequestHandler->renderAs($this, 'json');
		}
	}

}

?>