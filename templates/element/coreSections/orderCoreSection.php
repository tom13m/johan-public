<div class="coreSectionRow row">
	<div class="col-md-12">
		<!-- Top bar -->
		<div class="row">
			<div class="col-md-12">
				<div class="topBar row">
					<div class="topBarTitle col-md-3"> Bestellen </div>
					<div class="topBarSubtitle col-md-9">  </div>			
				</div>
			</div>
		</div>

		<!-- Scanned products overview -->
		<div class="coreSectionContent row">
			<div class="col-md-12">
				<div class="fullHeight row">
					<!-- Order list block -->
					<div class="orderLists col-md-3">
						<!-- Head order list -->
						<div class="orderListHead row">
							<div class="col-md-12">
								Bestellijsten
							</div>
						</div>

						<!-- Order list body -->
						<div class="orderListBody row">
							<!-- Active orders will be shown in this list -->
							<div id="orderList" class="col-md-12">
								<?php if (isset($data['orders'])) { 
									Foreach ($data['orders'] as $order) {
										echo $this->Element('coreSections/orderCoreSection/orderRow', ["order" => $order]); 
									}
								} ?>
							</div>
						</div>
						<div id="orderListAdd" class="orderListAddButton row">
							<div class="col-md-12">
								<?= $this->Form->create(null, ['id' => 'addOrderForm']); ?>

								<div class="row addOrderFormVisibleTitle">
									<div id="addOrderFormOpen" class="col-md-10">
										Nieuwe bestellijst	
									</div>
									<div class="col-md-2">
										<i id="addOrderFormCloseIcon" class="addOrderFormCloseIcon fas fa-times"> </i>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<p class="addOrderFormTitle"> Naam: </p>
									</div>
									<div class="col-md-9">
										<?= $this->Form->control('name', ['label' => false, 'class' => 'addOrderFormTextField']); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<p class="addOrderFormTitle"> Leverancier: </p>
									</div>
									<div class="col-md-8">
										<?= $this->Form->control('supplier_id', ['label' => false, 'class' => 'addOrderFormTextField', 'options' => $data['suppliersList']]); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<p class="addOrderFormTitle"> Autovul: </p>
									</div>
									<div class="col-md-9">
										<?= $this->Form->control('autoFill', ['label' => false, 'type' => 'checkbox', 'class' => 'addOrderFormCheckbox']); ?>
									</div>
								</div>
								<div class="row">
									<div id="addOrderFormSubmit" class="addOrderFormSubmit col-md-10 offset-md-1">
										Toevoegen
									</div>
								</div>

								<?= $this->Form->end(); ?>
							</div>
						</div>
					</div>

					<!-- Order content block -->
					<div class="col-md-9">
						<div class="fullHeight row">
							<div class="orderContent col-md-11">
								<!-- Head order content -->
								<div class="orderContentHead row">
									<div class="col-md-12">
										<div class="orderTitleHead row">
											<div id="orderProductsTitleTab" class="orderTitleTab active" onclick="switchOrderTab('products')">
												Producten
											</div>
											<div id="orderSendOptionsTitleTab" class="orderTitleTab"  onclick="switchOrderTab('sendOptions')">
												Verzendopties
											</div>

											<!-- Icon for activating remove option -->
											<div id="orderRemoveProductsIcon" class="orderRemoveProductsIcon" onclick="toggleRemoveProductsFromOrderMode()">
												<i class="fas fa-trash"> </i>
											</div>

											<!-- Icon for saving current order -->
											<i class="orderSaveIcon fas fa-save" onclick="saveOrder()"> </i>
										</div>
									</div>
								</div>

								<!-- Body order content -->
								<div class="orderContentBody row">
									<div class="col-md-12">
										<!-- Products tab -->
										<div id="orderProductsTab" class="orderTab active row">
											<div class="col-md-12">
												<div class="orderSpecHead row">
													<div class="col-md-4">
														Naam van het product
													</div>
													<div class="col-md-2">
														Leverancier
													</div>
													<div class="col-md-2">
														Voorraad
													</div>
													<div class="col-md-2">
														Min/Max
													</div>
													<div class="col-md-2">
														Aantal
													</div>
												</div>

												<!-- Order product sections will be displayed here -->
												<div id="orderProductsSectionParent" class="orderSection products row">
													<div id="orderProductsSection" class="col-md-12">
														
													</div>
												</div>
											</div>
										</div>

										<!-- Send options tab -->
										<div id="orderSendOptionsTab" class="orderTab row">
											<!-- Order product sections will be displayed here -->
											<div id="orderSendOptionsSection" class="orderSection col-md-12">
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- Temporary script -->
<script>
	/* Function for toggling the remove mode to remove products from an order */
	function toggleRemoveProductsFromOrderMode() {
		/* Check if a order is opened on the page */
		let orderListProductsSection = document.querySelector('.orderProducts.active');

		if (orderListProductsSection != null) {
			let orderRemoveProductsIcon = document.getElementById('orderRemoveProductsIcon');

			if (orderRemoveProductsIcon.classList.contains('active')) {
				/* Disable remove mode and remove products from order */
				orderRemoveProductsIcon.classList.remove('active');

				let orderId = orderListProductsSection.id.replace('orderProducts', '');

				let orderProductRowsDivId = 'orderProductRows' + orderId;
				let orderProductRows = document.getElementById(orderProductRowsDivId).querySelectorAll('.orderProductRow');
				let orderListItem = document.getElementById('orderListItem' + orderId).setAttribute('removeMode', 'false');

				$(orderProductRows).each(function(i, orderProductRow) {
					$(orderProductRow).off('click');
				});
			} else {
				/* Activate remove mode */
				orderRemoveProductsIcon.classList.add('active');

				let orderId = orderListProductsSection.id.replace('orderProducts', '');

				let orderProductRowsDivId = 'orderProductRows' + orderId;
				let orderProductRows = document.getElementById(orderProductRowsDivId).querySelectorAll('.orderProductRow');
				let orderListItem = document.getElementById('orderListItem' + orderId).setAttribute('removeMode', 'true');

				$(orderProductRows).each(function(i, orderProductRow) {
					$(orderProductRow).on('click', function() {
						toggleOrderProductActive(orderProductRow.id);
					});
				});
			}
		} else {
			/* Do not activate remove mode */
		}
	}

	/* Function for adding a product to the opened order list */
	function addProductToOrder(barcode) {
		/* Check if a order is opened on the page */
		let orderListProductsSection = document.querySelector('.orderProducts.active');

		if (orderListProductsSection != null) {
			/* Send add request to controller */
			let data = {};
			data['barcode'] = barcode;
			let orderId = data['orderId'] = orderListProductsSection.id.replace('orderProducts', '');

			ajaxRequest('Orders', 'addProductToOrder', data, process);

			function process(data, success) {
				if (success == 2) {
					/* Scroll to order product row */
					let orderProductRow;

					$('[barcode = ' + data['orderProduct']['barcode'] + ']').each(function(i, row) {
						if (row.getAttribute('order') == data['orderProduct']['orderId']) {
							orderProductRow = row;
						}
					});

					document.getElementById('orderProductsSectionParent').scrollTop = orderProductRow.offsetTop;

					/* Create short highlight */
					let i = 0;

					let interval = setInterval(function() { 
						orderProductRow.classList.toggle('highlight');
						i++;

						if (i >= 6) {
							clearInterval(interval);
						}
					}, 500);
				} else if (success != 0) {
					/* Add new product of order row to view */
					let elementPath = 'coreSections_orderCoreSection_orderProductRow';
					let elementId = 'orderProductRows' + orderId;

					/* Check if it is the first product to be added to the order */
					if (data['orderProduct']['first'] == true) {
						$('#' + elementId).empty();
					}

					renderElementAppend(elementPath, data, elementId);
				} else {
					/* Give error message */
					giveError(data['errorTemplate']);
				}
			}
		} else {
			/* Give message */

		}
	}

	/* Function for toggling an order product active for removing */
	function toggleOrderProductActive(id) {
		let element = document.getElementById(id);

		element.classList.toggle('active');

		/* If activated disable form fields */
		let orderProductIdField = element.id + 'ProductField';
		let orderProductAmountField = element.id + 'AmountField';

		if (element.classList.contains('active')) {
			//			console.log(orderProductIdField);

			document.getElementById(orderProductIdField).disabled = true;
			document.getElementById(orderProductAmountField).disabled = true;
		} else {
			document.getElementById(orderProductIdField).disabled = false;
			document.getElementById(orderProductAmountField).disabled = false;
		}
	}

	/* Function for saving an order */
	function saveOrder() {
		/* Checking if and which a form is opened */
		let orderListProductsSection = document.querySelector('.orderProducts.active');

		if (orderListProductsSection != null) {
			/* Getting order form data */
			let orderId = orderListProductsSection.id.replace('orderProducts', '');
			let form = document.getElementById('orderProductsForm' + orderId);
			let formData = serializeFormData(form);

			/* Saving order */
			ajaxRequest('Orders', 'saveOrder', formData, process);

			function process(data) {
				/* Checking if products need to be removed */
				let orderProductRows = $('[order = ' + data['order']['id'] + '].orderProductRow');

				$(orderProductRows).each(function(i, orderProductRow) {
					if (!data['barcodeArray'].includes(orderProductRow.getAttribute('barcode'))) {
						orderProductRow.remove();
					}
				});
			}
		} else {
			/* Do not execute save action */
		}
	}

	/* Function for displaying an order */
	function displayOrder(orderId, orderTabId) {
		/* Check if order is already opened */
		if (orderProducts = document.getElementById('orderProducts' + orderId) != null) {
			/* Display already opened order */
			setActive('orderProducts', 'orderProducts' + orderId);

			/* Set active order tab */
			setActive('orderListItem', orderTabId);

			/* Check if remove mode is activated */
			let orderListItem = document.getElementById('orderListItem' + orderId);
			let orderRemoveProductsIcon = document.getElementById('orderRemoveProductsIcon');

			if (orderListItem.getAttribute('removeMode') == 'true') {
				orderRemoveProductsIcon.classList.add('active');
			} else {
				orderRemoveProductsIcon.classList.remove('active');
			}
		} else {
			/* Open unopened order */
			let data = {};
			data['orderId'] = orderId;

			ajaxRequest('Orders', 'getOrderData', data, process);

			function process(data) {
				/* Check for and close an opened order */
				let activeOrder = document.querySelector('.orderProducts.active');

				if (activeOrder != null) {
					activeOrder.classList.remove('active');
				}

				/* Render order products */
				let elementPath = 'coreSections_orderCoreSection_orderProducts';
				let elementId = 'orderProductsSection';

				renderElementAppend(elementPath, data, elementId);
						
				/* Render send options */
				elementPath = 'coreSections_orderCoreSection_orderSendOptions';
				elementId = 'orderSendOptionsSection';
				
				renderElement(elementPath, data, elementId);

				/* Set active order tab */	
				setActive('orderListItem', orderTabId);

				/* Disable remove mode for order */
				let orderRemoveProductsIcon = document.getElementById('orderRemoveProductsIcon');
				orderRemoveProductsIcon.classList.remove('active');
			}
		}
	}

	/* Function for adding a new order list */
	function addOrderList() {
		let form = document.getElementById('addOrderForm');

		let formData = serializeFormData(form);

		ajaxRequest('Orders', 'addOrder', formData, process);

		function process(data) {
			/* Close add order section */
			toggleAddOrderList(true);

			/* Prepend order row */
			let elementPath = 'coreSections_orderCoreSection_orderRow';
			let elementId = 'orderList';

			renderElementAppend(elementPath, data, elementId, openRender);
								
			function openRender() {
				let orderTabId = 'orderListItem' + data['order']['id'];
				
				displayOrder(data['order']['id'], orderTabId);
			}
		}
	}

	/* Function for switching order tabs */
	function switchOrderTab(tab) {
		/* Title tabs */
		let titleTabId = 'order' + capitalize(tab) + 'TitleTab';

		setActive('orderTitleTab', titleTabId);

		/* Content tabs */
		let tabId = 'order' + capitalize(tab) + 'Tab';

		setActive('orderTab', tabId);
	}


	/* Function for toggling the add order list section */
	function toggleAddOrderList(close = false) {	
		let addOrderList = document.getElementById('orderListAdd');
		let addOrderFormOpen = document.getElementById('addOrderFormOpen');
		let closeIcon = document.getElementById('addOrderFormCloseIcon');

		if (addOrderList.classList.contains('active') && close != false) {
			addOrderList.classList.remove('active');
			closeIcon.classList.remove('active');

			$(addOrderFormOpen).on('click', function() {
				toggleAddOrderList(true);
			});
		} else if (!addOrderList.classList.contains('active')) {
			$(addOrderFormOpen).off("click");

			addOrderList.classList.add('active');
			closeIcon.classList.add('active');
		}
	}
	
	/* Toggle customer order in order send options */
	function toggleOrderSendOptionsCustomer(checked) {
		let customerField = document.getElementById('orderSendOptionsCustomerField');
		
		if (checked == true) {
			/* Allow customer order */
			customerField.disabled = false;
		} else {
			/* Block customer order */
			customerField.disabled = true;
		}
	}

	function bindEventListenersOrder() {
		/* toggleAddOrderList function */
		let addOrderFormOpen = document.getElementById('addOrderFormOpen');
		let closeIcon = document.getElementById('addOrderFormCloseIcon');

		$(addOrderFormOpen).on('click', function() {
			toggleAddOrderList();
		});
		$(closeIcon).on('click', function() { toggleAddOrderList(1) });

		/* addOrderList function */
		let addOrderFormSubmit = document.getElementById('addOrderFormSubmit');

		addOrderFormSubmit.addEventListener('click', function() { addOrderList() });
	}

	bindEventListenersOrder();
</script>