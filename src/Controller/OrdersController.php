<?php

namespace App\Controller;

use Cake\Controller\Controller;

class OrdersController extends AppController {

	public function initialize(): void {
		parent::initialize();

		$this->viewBuilder()->setLayout('main');
	}
	
	public function getOrderData() {
		$response = [];
		
		if ($this->request->is('post')) {
			/* Receiving data */
			$data = $this->request->getData('data');
			
			$order = $this->Orders->findById($data['orderId'])->first();
			$order->products = unserialize($order->products);
			
			/* Resetting data array */
			$data = [];
				$data['order'] = $order;
			
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

			/* Checking if new order needs to auto filled */
			if ($data['autoFill'] == true) {
				/* Add a prefilled order list */

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
			
			
			/* Resetting data array */
			
			
			$response['data'] = $data;
		} else {
			$response['success'] = 0;
		}
		
		$this->set(compact('response'));
		$this->viewBuilder()->setOption('serialize', true);
		$this->RequestHandler->renderAs($this, 'json');
	}

}

?>