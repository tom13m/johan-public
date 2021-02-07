<div class="coreSectionRow row">

	<div class="col-md-12">
		<!-- Top bar -->
		<div class="row">
			<div class="col-md-12">
				<div class="topBar row">
					<div class="topBarTitle col-md-3"> Inventarisatie </div>
					<div class="topBarSubtitle col-md-9">  </div>			
				</div>
			</div>
		</div>

		<!-- Scanned products overview -->
		<div class="coreSectionContent row">
			<div class="col-md-12">
				<div class="fullHeight row">
					<div class="col-md-8">
						<!-- Head products -->
						<div class="stocktakingHead row">
							<div class="col-md-12">
								<div class="stocktakingTitleHead row">
									<div class="col-md-11">
										Gescande producten
									</div>
									<div class="col-md-1"> <i class="stocktakingSaveIcon fas fa-save" onclick="saveProductsSet()"> </i> </div>
								</div>
								<div class="stocktakingSpecHead row">
									<div class="col-md-6">
										Naam van het product
									</div>
									<div class="col-md-3">
										Boekingreden
									</div>
									<div class="col-md-3">
										Aantal
									</div>
								</div>
							</div>
						</div>
						<!-- Body products -->
						<div class="stocktakingProductsBody row">
							<?= $this->Form->create(null, ['id' => 'stocktakingFormProducts']); ?>

							<!-- All scanned products will be shown here -->
							<div id="stocktakingProductsBody" class="col-md-12">

							</div>

							<?= $this->Form->hidden('key', ['id' => 'stocktakingProductKeyField']); ?>

							<?= $this->Form->end(); ?>
						</div>
					</div>

					<div class="stocktakingOptionBlock col-md-4">
						<!-- Head options -->
						<div class="stocktakingOptionsHead row">
							<div id="stocktakingHeadOptions" class="stocktakingHeadOption active col-md-4 offset-md-2" onclick="switchStocktakingHeadOption('options')">
								Opties
							</div>
							<div id="stocktakingHeadSaves" class="stocktakingHeadOption col-md-4" onclick="switchStocktakingHeadOption('saves')">
								Opgeslagen
							</div>
						</div>

						<!-- Body options -->
						<div class="row">
							<div class="stocktakingOptionsBody col-md-10 offset-md-1">
								<div id="stocktakingOptions" class="stocktakingSubBlock active row">
									<div class="col-md-12">
										<?= $this->Form->create(null, ['id' => 'stocktakingFormOptions']); ?>

										<div class="stocktakingOptionsRow row">
											<div id="stocktakingSubCorrection" class="stocktakingOption active col-md-3 offset-md-2" onclick="switchStocktakingSubOption('correction')">
												<i class="fas fa-trash"> </i>
											</div>
											<div id="stocktakingSubMovement" class="stocktakingOption col-md-3 offset-md-1" onclick="switchStocktakingSubOption('movement')">
												<i class="fas fa-shuttle-van"> </i>
											</div>

											<?= $this->Form->hidden('stocktakingOption', ['id' => 'stocktakingOption', 'value' => 'correction']); ?>
										</div>

										<div id="stocktakingStockCorrection" class="stocktakingOptionSubSection active row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-10 offset-md-1">
														<p> Magazijn: </p>

														<?= $this->Form->control('warehouse_id', ['label' => false, 'options' => $data['warehousesList']]); ?>
													</div>
												</div>
											</div>
										</div>

										<div id="stocktakingStockMovement" class="stocktakingOptionSubSection row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-10 offset-md-1">
														<p> Van magazijn: </p>

														<?= $this->Form->control('from_warehouse_id', ['label' => false, 'options' => $data['warehousesList']]); ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-10 offset-md-1">
														<p> Naar magazijn: </p>

														<?= $this->Form->control('to_warehouse_id', ['label' => false, 'options' => $data['warehousesList']]); ?>
													</div>
												</div>
											</div>
										</div>

										<?= $this->Form->end(); ?>
									</div>
								</div>

								<div id="stocktakingSaves" class="stocktakingSubBlock row">

								</div>
							</div>
						</div>

						<div class="row">
							<div class="stocktakingSubmitSection col-md-10 offset-md-1" onclick="executeStocktaking()">
								<p> Uitvoeren </p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- Temporary scripts for testing -->
<script>

	/* Function for executing a stocktaking */
	function executeStocktaking() {
		let productsForm = document.getElementById('stocktakingFormProducts');
		let optionsForm = document.getElementById('stocktakingFormOptions');

		let formData = serializeFormData([productsForm, optionsForm]);
		let formOption = formData['stocktakingOption'];

		if (formOption == 'correction') {
			ajaxRequest('Products', 'correctProductsStocks', formData, process);
		} else if (formOption == 'movement') {
			ajaxRequest('Products', 'moveProductsStocks', formData, process);
		}

		function process(data) {
			/* Resetting products body and indicating success */
			let productsBody = document.getElementById('stocktakingProductsBody');

			$(productsBody).empty();

			let elementPath = 'coreSections_stocktakingCoreSection_successRow';
			let elementId = 'stocktakingProductsBody';

			renderElementPrepend(elementPath, data, elementId);

			setTimeout(function() {
				$(productsBody).empty();
			}, 2000);
		}
	}

	/* Function for adding a product to the stocktaking section */
	function addStocktakingProduct(barcode = null) {
		if (barcode != null) {
			let data = {
				'barcode': barcode
			}

			ajaxRequest('Products', 'getProductData', data, process);

			function process(data) {
				let elementPath = 'coreSections_stocktakingCoreSection_productRow';
				let elementId = 'stocktakingProductsBody';

				renderElementPrepend(elementPath, data, elementId);
			}
		}
	}

	function saveProductsSet() {	
		let form = document.getElementById('stocktakingFormProducts');

		let formData = serializeFormData(form);

		console.log(formData);


	}

	function switchStocktakingHeadOption(headOption) {
		/* Setting actives */
		setActive('stocktakingHeadOption', 'stocktakingHead' + capitalize(headOption));

		setActive('stocktakingSubBlock', 'stocktaking' + capitalize(headOption));
	}

	function switchStocktakingSubOption(subOption) {
		/* Setting actives */
		setActive('stocktakingOption', 'stocktakingSub' + capitalize(subOption));

		setActive('stocktakingOptionSubSection', 'stocktakingStock' + capitalize(subOption));

		/* Set form option value */
		document.getElementById('stocktakingOption').value = subOption;
	}

</script>