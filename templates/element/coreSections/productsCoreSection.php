<div class="row coreSectionRow">
	
	<div class="col-md-7">
		<!-- Products table -->
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6">
						Producten
					</div>
					<div class="col-md-6">
						<?= $this->Form->create(null, ['id' => 'importProductsForm', 'type' => 'file', 'action' => $this->Url->Build(['controller' => 'Products', 'action' => 'importProducts'])]); ?>
							
							<?= $this->Form->control('products_file', ['label' => false, 'type' => 'file']); ?>
							
<!--							<button type="button" class="moveProductStockButton" onclick="importProducts()"> Voer uit </button>-->
						<?= $this->Form->submit(); ?>
						
						<?= $this->Form->end(); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<table>
							<thead>
								<tr>
									<th>
										Barcode
									</th>
									<th>
										Naam
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										123456
									</td>
									<td>
										Testproduct
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Products action scenes -->
	<div id="productsActionMenu" class="actionMenu col-md-4 offset-md-1">
		
	</div>
	
</div>

<script>
	function importProducts() {
		/* Function for moving product stocks */
		let form = document.getElementById('importProductsForm');
		
		/* Getting form data */
		let formData = serializeFormData(form);
		
		/* Moving product stock */
		ajaxRequest('Products', 'importProducts', formData, processImportProducts);
		
		/* Processing product stock movement to view */
		function processImportProducts(data) {
			console.log(data);
		}
	}
</script>