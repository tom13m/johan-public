<!-- Product row for stocktaking products block -->
<div id="stocktakingProduct" class="stocktakingProductRow prepended row">
	<div class="col-12">
		<div class="row">
			<div class="col-12">
				<p class="stocktakingProductNameField"> <?= $data['product']['name']; ?> </p>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<?= $this->Form->control('bookingReasons[]', ['label' => false, 'class' => 'stocktakingBookingReasonField', 'options' => $data['bookingReasonsList']]); ?>
			</div>
			<div class="col-6">
				<?= $this->Form->control('amounts[]', ['label' => false, 'type' => 'number', 'default' => 0, 'class' => 'stockTakingProductAmountField']); ?>
			</div>
		</div>
	</div>
</div>

<?= $this->Form->hidden('products[]', ['value' => $data['product']['id']]); ?>