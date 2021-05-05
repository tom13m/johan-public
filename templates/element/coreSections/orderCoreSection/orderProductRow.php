<!-- Element for a product row inside a products section of an order -->
<div id="<?= $data['orderProduct']['orderId'] . 'orderProductRow' . $data['orderProduct']['id']; ?>" class="orderProductRow row" order="<?= $data['orderProduct']['orderId']; ?>" barcode="<?= $data['orderProduct']['barcode']; ?>">
	<div class="textOverflow col-md-4">		
		<?= $data['orderProduct']['name']; ?>
		
		<?= $this->Form->hidden('barcode[]', ['value' => $data['orderProduct']['barcode'], 'id' => $data['orderProduct']['orderId'] . 'orderProductRow' . $data['orderProduct']['id'] . 'ProductField']); ?>
	</div>
	<div class="textOverflow col-md-2">
		<?= $data['orderProduct']['supplier']['name']; ?>
	</div>
	<div class="col-md-2">
		<?= $data['orderProduct']['stock']; ?>
	</div>
	<div class="col-md-2">
		<?= $data['orderProduct']['minMax']; ?>
	</div>
	<div class="col-md-2">
		<?= $this->Form->control('amount[]', ['value' => $data['orderProduct']['amount'], 'label' => false, 'type' => 'number', 'default' => 0, 'class' => 'orderProductRowAmountField', 'id' => $data['orderProduct']['orderId'] . 'orderProductRow' . $data['orderProduct']['id'] . 'AmountField']); ?>
	</div>
</div>