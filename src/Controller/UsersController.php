<?php

namespace App\Controller;

use Cake\Controller\Controller;

class UsersController extends AppController {
	
	public function initialize(): void {
		parent::initialize();
		
		$this->viewBuilder()->setLayout('auth');
	}
	
	public function beforeFilter(\Cake\Event\EventInterface $event) {
		parent::beforeFilter($event);
		// Configure the login action to not require authentication, preventing
		// the infinite redirect loop issue
		$this->Authentication->addUnauthenticatedActions(['login', 'add']);
	}
	
	public function login() {
		$this->request->allowMethod(['get', 'post']);
		
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {
			// redirect to /articles after login success

			return $this->redirect(['controller' => 'main', 'action' => 'index']);
		}
		// display error if user submitted and authentication failed
		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error(__('Invalid username or password'));
		}
	}
	
	public function logout() {
		$result = $this->Authentication->getResult();
		
		if ($result->isValid()) {
			$this->Authentication->logout();
			return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}
	}
	
	public function add() {
		if ($this->request->is('post')) {
			$data = $this->request->getData();
			
			$user = $this->Users->newEmptyEntity();
			$user = $this->Users->patchEntity($user, $data);
			
			if ($this->Users->save($user)) {
				$this->Flash->success('User toegevoegd');
				return $this->redirect(['controller' => 'users', 'action' => 'login']);
			} else {
				$this->Flash->error('User kon niet worden toegevoegd');
			}
		}
	}
}

?>