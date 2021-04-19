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
								<?php if (isset($data['orders'])) { Foreach ($data['orders'] as $order) {
	echo $this->Element('coreSections/orderCoreSection/orderRow', ["order" => $order]);
}} ?>
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
										<?= $this->Form->control('name', ['label' => false, 'class' => 'addOrderFormNameField']); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<?= $this->Form->control('autoFill', ['label' => false, 'type' => 'checkbox', 'class' => 'addOrderFormCheckbox']); ?>
									</div>
									<div class="col-md-10">
										<p class="addOrderFormTitle"> Autovul </p>
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
							<div class="orderContent col-md-10">
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

											<i class="orderSaveIcon fas fa-save" onclick=""> </i>
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
													<div class="col-md-5">
														Naam van het product
													</div>
													<div class="col-md-2">
														Leverancier
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
											Test 2

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
//					for (i = 0; i <= 3; i++) {
//						orderProductRow.classList.add('highlight');
//						
//						setInterval(function() { 
//							orderProductRow.classList.remove('highlight');
//							
//							continue;
//						}, 500);
//					}
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

				}
			}
		} else {
			/* Give message */

		}
	}

	/* Function for removing a product from an order list */
	function removeProductFromOrder(barcode) {

	}

	/* Function for displaying an order */
	function displayOrder(orderId, orderTabId) {
		/* Check if order is already opened */
		if (orderProducts = document.getElementById('orderProducts' + orderId) != null) {
			/* Display already opened order */
			setActive('orderProducts', 'orderProducts' + orderId);

			/* Set active order tab */
			setActive('orderListItem', orderTabId);
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

				/* Set active order tab */	
				setActive('orderListItem', orderTabId);
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

			renderElementAppend(elementPath, data, elementId);
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