<!-- Order row element for order list -->
<?php if (isset($data['order'])) { ?>

<div class="orderListItem appended row" onclick="displayOrder(<?= $data['order']['id']; ?>)">
	<div class="col-md-12">
		<?= $data['order']['name']; ?>
	</div>
</div>

<?php } else { ?>

<div class="orderListItem row" onclick="displayOrder(<?= $order['id']; ?>)">
	<div class="col-md-12">
		<?= $order['name']; ?>
	</div>
</div>

<?php } ?>