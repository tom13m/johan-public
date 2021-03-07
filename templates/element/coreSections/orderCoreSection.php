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
							<div class="col-md-12">
								<div class="orderListItem row">
									<div class="col-md-12">
										Test Bestellijst
									</div>
								</div>
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
											<div class="orderTitleTab active">
												Producten
											</div>
											<div class="orderTitleTab">
												Verzendopties
											</div>

											<i class="orderSaveIcon fas fa-save" onclick=""> </i>
										</div>
										<div class="orderSpecHead row">
											<div class="col-md-6">
												Naam van het product
											</div>
											<div class="col-md-2">
												Aantal
											</div>
											<div class="col-md-3">
												Leverancier
											</div>
										</div>
									</div>
								</div>

								<!-- Body order content -->
								<div class="orderContentBody row">
									<div class="col-md-12">

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
	/* Function for adding a new order list */
	function addOrderList() {
		let form = document.getElementById('addOrderForm');

		let formData = serializeFormData(form);

		console.log(formData);
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