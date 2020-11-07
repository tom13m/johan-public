<!-- Product stock element -->
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<p> Totale voorraad: </p>
		</div>
		<div class="row">
			<?php Foreach ($data['warehouses'] as $warehouse) { ?>
					
			<div class="productWarehouse col-md-3">
				<div class="row">
					<p> <?= $warehouse['name']; ?> </p>
				</div>
				<div class="row">
					<i class="fas fa-shuttle-van"> </i>
				</div>
				<div class="row">
					<p> Voorraad: <?= $warehouse['productStock']; ?> </p>
				</div>
			</div>
					
			<?php } ?>
		</div>
	</div>
</div>