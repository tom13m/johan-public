<!-- Element for a product row inside a products section of an order -->
<div class="orderProductRow row" order="<?= $data['orderProduct']['orderId']; ?>" barcode="<?= $data['orderProduct']['barcode']; ?>">
	<div class="textOverflow col-md-5">		
		<?= $data['orderProduct']['name']; ?>
		
		<?= $this->Form->hidden('products[]', ['value' => $data['orderProduct']['id'], 'id' => false]); ?>
	</div>
	<div class="textOverflow col-md-2">
		<?= $data['orderProduct']['supplier']['name']; ?>
	</div>
	<div class="col-md-2">
		<?= $data['orderProduct']['minMax']; ?>
	</div>
	<div class="col-md-2">
		<?= $this->Form->control('amount[]', ['label' => false, 'type' => 'number', 'default' => 0, 'class' => 'orderProductRowAmountField', 'id' => false]); ?>
	</div>
	<div class="col-md-1">
		<i class="fas fa-times"> </i>
	</div>
</div>