<!-- Product row for stocktaking products block -->
<div id="stocktakingProduct" class="stocktakingProductRow prepended row">
	<div class="col-md-6">
		<p class="stocktakingProductNameField"> <?= $data['product']['name']; ?> </p>
	</div>
	<div class="col-md-3">
		<?= $this->Form->control('bookingReasons[]', ['label' => false, 'options' => $data['bookingReasonsList']]); ?>
	</div>
	<div class="col-md-3">
		<?= $this->Form->control('amounts[]', ['label' => false, 'type' => 'number', 'default' => 0, 'class' => 'stockTakingProductAmountField']); ?>
	</div>
</div>

<?= $this->Form->hidden('products[]', ['value' => $data['product']['id']]); ?>