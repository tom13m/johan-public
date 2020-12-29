<!-- Temporary action section -->
<div class="scanContentDiv row">
	<div class="productStockDivTitle  col-12" style="background-color: var(--main-color);">
		<p> Magazijncorrectie </p>
	</div>

	<!-- Edit warehouse properties in scan action menu -->
	<?= $this->Form->create(null, ['id' => 'editWarehouseScanActionMenuForm']); ?>

	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="productMenuWarehouse col-12">
					<?= $this->Form->control('warehouse_id', ['id' => 'editWarehouseScanActionMenuWareHouseField', 'label' => false, 'options' => $data['warehousesList'], 
															  'class' => 'optionField warehouseOptionField', 'empty' => 'Selecteer een magazijn', 'onchange' => 'switchProductWarehouse(this.value, "edit")']); ?>
				</div>
				<div class="col-12">
					<!-- Warehouses options -->
					<div id="productCorrectionWarehouses">
						<?php Foreach ($data['warehouses'] as $warehouse) { ?>

						<div id="<?= 'productWarehouseOptionEdit' . $warehouse['id']; ?>" class="warehouseOption edit">
							<div class="row">
								<div class="col-12">
									<i class="fas fa-shuttle-van"> </i>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p> Minimale voorraad: </p>
									<?= $this->Form->control('minimum_stock' . $warehouse['id'], ['id' => 'editWarehouseScanActionMenuMinimumStockField' . $warehouse['id'], 'label' => false, 
																								  'class' => 'numericalField productWarehouseNumericalField', 'type' => 'number', 'value' => $warehouse['minimumStock']]); ?>
								</div>
								<div class="col-12">
									<p> Maximale voorraad: </p>
									<?= $this->Form->control('maximum_stock' . $warehouse['id'], ['id' => 'editWarehouseScanActionMenuMaximumStockField' . $warehouse['id'], 'label' => false, 
																								  'class' => 'numericalField productWarehouseNumericalField', 'type' => 'number', 'value' => $warehouse['maximumStock']]); ?>
								</div>
								<div class="col-12">
									<p> Voorraad: </p>
									<?= $this->Form->control('product_stock' . $warehouse['id'], ['id' => 'editWarehouseScanActionMenuStockField' . $warehouse['id'], 'label' => false, 
																								  'class' => 'numericalField productWarehouseNumericalField', 'type' => 'number', 'value' => $warehouse['productStock']]); ?>
								</div>
							</div>
						</div>

						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="actionMenuSubmitButtonSection col-12">
					<button type="button" class="actionMenuSubmitButton" onclick="editWarehouse()"> Opslaan </button>
				</div>
			</div>
		</div>
	</div>

	<?= $this->Form->control('product_id', ['type' => 'hidden', 'id' => 'productEditWarehouseProductId', 'value' => $data['product']['id']]); ?>
</div>