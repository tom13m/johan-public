<!-- Product stock element -->
<div class="scanContentDiv row">
	<div class="productStockDivTitle  col-12">
		<p> Magazijnvoorraad </p>
	</div>
	
	<div class="col-12">
		<div class="row">
			<?php Foreach ($data['warehouses'] as $warehouse) { ?>

			<div class="col-6">
				<div class="row">
					<div class="productStockDivWarehouse col-10 offset-1">
						<p class="productStockDivWarehouseTitle nop"> <?= $warehouse['name']; ?> </p>
						<p class="productStockDivWarehouseSubTitle nop"> Voorraad: <?= $warehouse['productStock']; ?> </p>
					</div>
				</div>
			</div>

			<?php } ?>
		</div>
	</div>
</div>