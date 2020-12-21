<div class="row coreSectionRow">

	<div class="col-md-12">
		<!-- Product information -->
		<div class="row">
			<div class="col-md-12">
				<div class="topBar row">
					<div class="topBarTitle col-md-3"> Productenoverzicht </div>
					<div class="topBarSubtitle col-md-9">  </div>			
				</div>
			</div>
		</div>
		<div class="coreSectionContent row">
			<div class="col-md-12">
				<!-- Action

<!-- Productstable -->
				<div class="row">
					<div class="col-md-12">
						<table>
							<tr>
								<th> Naam </th>
							</tr>

							<?php Foreach ($data['products'] as $product) { ?>
							<tr>
								<td> <?= $product['name']; ?> </td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
				<div class="row">
					<?= $this->Form->create(null, ['id' => 'importProductsForm', 'type' => 'file', 'action' => $this->Url->Build(['controller' => 'Products', 'action' => 'importProducts'])]); ?>

					<?= $this->Form->control('products_file', ['label' => false, 'type' => 'file', 'id' => 'fileTest']); ?>

					<?php 
					if (isset($data['fileFormatsList'])) { 
						echo $this->Form->control('file_format_id', ['label' => false, 'options' => $data['fileFormatsList']]);
					} else {
						echo 'Geen file formaten';
					}
					?>

<!--					<button type="button" class="moveProductStockButton" onclick="importProducts()"> Voer uit </button>-->

					<?= $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
	/* Function for moving product stocks */
	function importProducts() {
		/* Getting form data */
		let form = document.getElementById('importProductsForm');
		//		let formData = serializeFormData(form);
		
//		let file = form.products_file.files[0];
//		var test = $.csv.toObjects(file);
//		
//		console.log(test);

		let formData = new FormData();
//		
////		console.log(form.products_file);
//		
		let file = $(form.products_file).prop('files')[0];
//		console.log(file);
        formData.append("csv_file", file);
		
//		console.log(form.products_file.files[0]);

//		formData.append('products_file', form.products_file.files[0]);
//		formData.append('test', 'test');
		
//		JSON.stringify(formData);
		
//		formData.append('file_format_id', form.file_format_id);

//		console.log(formData);

		/* Moving product stock */
		ajaxRequestFile('Products', 'importProducts', formData, processImportProducts);

		/* Processing product stock movement to view */
		function processImportProducts(data) {
			console.log(data);
		}
	}
</script>