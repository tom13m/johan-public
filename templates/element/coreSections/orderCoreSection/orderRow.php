<!-- Order row element for order list -->
<?php if (isset($data['order'])) { ?>

<div id="<?= 'orderListItem' . $data['order']['id']; ?>" class="orderListItem appended row" onclick="displayOrder(<?= $data['order']['id']; ?>, this.id)">
	<div class="col-md-12">
		<?= $data['order']['name']; ?>
	</div>
</div>

<?php } else { ?>

<div id="<?= 'orderListItem' . $order['id']; ?>" class="orderListItem row" onclick="displayOrder(<?= $order['id']; ?>, this.id)">
	<div class="col-md-12">
		<?= $order['name']; ?>
	</div>
</div>

<?php } ?>