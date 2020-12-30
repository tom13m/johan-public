<!-- Temporary action section -->
<div class="scanContentDiv row">
	<div class="productStockDivTitle  col-12" style="background-color: var(--main-color);">
		<p> Nieuw product </p>
	</div>

	<div class="col-md-12">
		<?= $this->Form->create(null, ['id' => 'addProductForm2']); ?>

		<?= $this->Form->control('barcode', ['id' => 'productFormBarcode', 'label' => false, 'placeholder' => 'Barcode']); ?>
		<?= $this->Form->control('name', ['id' => 'productFormName', 'label' => false, 'placeholder' => 'Naam']); ?>
		<?= $this->Form->control('description', ['id' => 'productFormDescription', 'label' => false, 'type' => 'textarea', 'placeholder' => 'Omschrijving']); ?>
		<?= $this->Form->control('supplier_id', ['id' => 'productFormSupplierId', 'label' => false, 'options' => $data['suppliersList']]); ?>

		<button type="button" class="addProductButton" onclick="addProduct()"> Voeg toe </button>

		<?= $this->Form->end(); ?>
	</div>
</div>

<script>
	/* Temporary function for adding a new product */
	function addProduct() {
		/* Receiving form data */
		let form = document.getElementById('addProductForm2');
		
		console.log(form);

		let formData = serializeFormData(form);

		console.log(formData);

		ajaxRequest('Products', 'addProduct', formData, next);

		function next(data) {
			//		giveError(data['errorTemplate']);
		}
	}
</script>