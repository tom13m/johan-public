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
					<div class="col-md-6">
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
					
					<div class="col-md-4 offset-md-2">
						<div class="row">
							<div class="col-md-12">
								<p> Nieuw product toevoegen </p>
							</div>
							
							<div class="col-md-12">
							<?= $this->Form->create(null, ['id' => 'addProductForm']); ?>
							
							<?= $this->Form->control('barcode', ['id' => 'productFormBarcode', 'label' => false, 'placeholder' => 'Barcode']); ?>
							<?= $this->Form->control('name', ['id' => 'productFormName', 'label' => false, 'placeholder' => 'Naam']); ?>
							<?= $this->Form->control('description', ['id' => 'productFormDescription', 'label' => false, 'type' => 'textarea', 'placeholder' => 'Omschrijving']); ?>
							<?= $this->Form->control('supplier_id', ['id' => 'productFormSupplierId', 'label' => false, 'options' => $data['suppliersList']]); ?>
							
							<button type="button" class="addProductButton" onclick="addProduct()"> Voeg toe </button>
								
							<?= $this->Form->end(); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<?= $this->Form->create(null, ['id' => 'importProductsForm', 'type' => 'file']); ?>

					<?= $this->Form->control('products_file', ['label' => false, 'type' => 'file', 'id' => 'fileTest']); ?>

					<?php 
					if (isset($data['fileFormatsList'])) {
						echo $this->Form->control('file_format_id', ['label' => false, 'options' => $data['fileFormatsList']]);
					} else {
						echo 'Geen file formaten';
					}
					?>

					<button type="button" class="moveProductStockButton" onclick="importProducts()"> Voer uit </button>

					<?= $this->Form->end(); ?>
					
					<div id='out'>  </div>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
	/* Temporary function for adding a new product */
	function addProduct() {
		/* Receiving form data */
		let form = document.getElementById('addProductForm');
		
		let formData = serializeFormData(form);
		
		ajaxRequest('Products', 'addProduct', formData, next);
		
		function next(data) {
			giveError(data['errorTemplate']);
		}
	}
	
	/* Function for moving product stocks -> to be implemented in general function for file upload */
	function importProducts() {
		let form = document.getElementById('importProductsForm');
		
		let formData = serializeFormData(form);
		let fileType = form.products_file.files[0]['name'].split('.').pop();
		
		let reader_csv = new FileReader();
		let reader_xlsx = new FileReader();
		
		/* Reader for csv file */
        reader_csv.onload = function() {
            let fileResult = reader_csv.result;
			
			let csvData = $.csv.toArrays(fileResult.replaceAll('"', ""));
			
			formData['products_file'] = csvData;
			
			/* Moving product stock */
			ajaxRequest('Products', 'importProducts', formData, processImportProducts);
        };
		
		/* Reader for xlsx file */
		reader_xlsx.onload = function() {
			let fileResult = reader_xlsx.result;
		  	let workbook = XLSX.read(fileResult, {
				type: 'binary'
		  	});
			
			workbook.SheetNames.forEach(function(sheetName) {
				// Here is your object
				let XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
				
				formData['products_file'] = XL_row_object;
				
				ajaxRequest('Products', 'importProducts', formData, processImportProducts);
			});
		}
		
        // start reading the file. When it is done, calls the onload event defined above.
		if (fileType == 'csv') {
        	reader_csv.readAsText(form.products_file.files[0]);
		} else if (fileType == 'xlsx') {
			reader_xlsx.readAsBinaryString(form.products_file.files[0]);
		}
		
		/* Reseting file field of form */
		form.products_file.value = '';
		
		/* Processing product stock movement to view */
		function processImportProducts(data) {
			console.log(data);
		}
	}
</script>