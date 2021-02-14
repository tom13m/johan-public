<div class="coreSectionRow row">
	<div class="col-12">
		<!-- Top bar -->
		<div class="topBar row">
			<div class="topBarTitle col-12"> Inventarisatie </div>			
		</div>

		<!-- Main options -->

		<!-- Product information -->
		<div class="coreSectionContent static row">
			<div class="col-12">
				<div class="row">
					<div id="stocktakingHeadProducts" class="stocktakingHeadOption active col-5 offset-1" onclick="switchStocktakingHeadOption('products')">
						Producten
					</div>
					<div id="stocktakingHeadOptions" class="stocktakingHeadOption col-5" onclick="switchStocktakingHeadOption('options')">
						Opties
					</div>
				</div>

				<div id="stocktakingProducts" class="stocktakingSubBlock active row">
					<?= $this->Form->create(null, ['id' => 'stocktakingFormProducts']); ?>
					<!-- All scanned products will be shown here -->
					<div id="stocktakingProductsBody" class="stocktakingProductsSection col-12">

					</div>
					<?= $this->Form->end(); ?>
				</div>

				<div id="stocktakingOptions" class="stocktakingSubBlock row">
					<?= $this->Form->create(null, ['id' => 'stocktakingFormOptions', 'class' => 'fullWidth']); ?>

					<div class="col-12">
						<div class="row">
							<div id="stocktakingSubCorrection" class="stocktakingOption active col-4 offset-1" onclick="switchStocktakingSubOption('correction')">
								<i class="fas fa-trash"> </i>
							</div>
							<div id="stocktakingSubMovement" class="stocktakingOption col-4 offset-1" onclick="switchStocktakingSubOption('movement')">
								<i class="fas fa-shuttle-van"> </i>
							</div>
							
							<?= $this->Form->hidden('stocktakingOption', ['id' => 'stocktakingOption', 'value' => 'correction']); ?>
						</div>

						<div id="stocktakingStockCorrection" class="stocktakingOptionSubSection active row">
							<div class="col-12">
								<div class="row">
									<div class="col-10 offset-1">
										<p> Magazijn: </p>

										<?= $this->Form->control('warehouse_id', ['label' => false, 'options' => $data['warehousesList']]); ?>
									</div>
								</div>
							</div>
						</div>

						<div id="stocktakingStockMovement" class="stocktakingOptionSubSection row">
							<div class="col-12">
								<div class="row">
									<div class="col-10 offset-1">
										<p> Van magazijn: </p>

										<?= $this->Form->control('from_warehouse_id', ['label' => false, 'options' => $data['warehousesList']]); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-10 offset-1">
										<p> Naar magazijn: </p>

										<?= $this->Form->control('to_warehouse_id', ['label' => false, 'options' => $data['warehousesList']]); ?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="stocktakingSubmitSection col-10 offset-1" onclick="executeStocktaking()">
								<p> Uitvoeren </p>
							</div>
						</div>
					</div>

					<?= $this->Form->end(); ?>
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
			
			switchStocktakingHeadOption('products');

			let elementPath = 'coreSections_mobile_stocktakingCoreSection_successRow';
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
				let elementPath = 'coreSections_mobile_stocktakingCoreSection_productRow';
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