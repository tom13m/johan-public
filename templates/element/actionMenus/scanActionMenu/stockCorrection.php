<!-- Change product stock of warehouse -->
<?= $this->Form->create(null, ['id' => 'correctProductStockForm']); ?>

<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="productMenuWarehouse col-md-12">
				<?= $this->Form->control('warehouse_id', ['id' => 'stockCorrectionWareHouseField', 'label' => false, 'options' => $data['warehousesList'], 
															   'class' => 'optionField warehouseOptionField', 'empty' => 'Selecteer een magazijn', 'onchange' => 'switchProductWarehouse(this.value, "correction")']); ?>
			</div>
			<div class="col-md-12">
				<!-- Warehouses options -->
				<div id="productCorrectionWarehouses">
					<?php Foreach ($data['warehouses'] as $warehouse) { ?>

					<div id="<?= 'productWarehouseOptionCorrection' . $warehouse['id']; ?>" class="warehouseOption correction">
						<div class="row">
							<div class="col-md-12">
								<i class="fas fa-shuttle-van"> </i>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<p> Voorraad: <?= $warehouse['productStock']; ?> </p>
							</div>
						</div>
					</div>

					<?php } ?>
				</div>
			</div>
		</div>
		<div class="productStockAmount row">
			<div class="col-md-2 offset-md-2">
				Aantal:
			</div>
			<div class='col-md-5 offset-md-1'>
				<?= $this->Form->control('amount', ['label' => false, 'id' => 'productStockCorrectionAmount', 'class' => 'numericalField productWarehouseNumericalField', 'type' => 'number']); ?>
			</div>
		</div>
		<div class="productStockBookingReason row">
			<div class="col-md-2 offset-md-1">
				Reden:
			</div>
			<div class='col-md-5 offset-md-1'>
				<?= $this->Form->control('booking_reason_id', ['label' => false, 'options' => $data['bookingReasonsList'], 'class' => 'optionField actionMenuOptionField']); ?>
			</div>
		</div>
		<div class="row">
			<div class="actionMenuSubmitButtonSection col-md-12">
				<button type="button" class="actionMenuSubmitButton" onclick="correctProductStock()"> Voer uit </button>
			</div>
		</div>
	</div>
</div>

<?= $this->Form->control('product_id', ['type' => 'hidden', 'id' => 'productStockCorrectionProductId', 'value' => $data['product']['id']]); ?>
<?= $this->Form->end(); ?>