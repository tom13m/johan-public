<!-- Product stock element -->
<div class="productStockDiv row">
	<div class="col-md-12">
		<div class="row">
			<p class="productStockDivTitle"> Magazijnvoorraad </p>
		</div>
	</div>
	<div class="col-md-12">
		<div class="row">
			<?php Foreach ($data['warehouses'] as $warehouse) { ?>
					
			<div class="productStockDivWarehouse col-md-3">	
				<div class="row">
					<div class="col-md-9">
						<p class="productStockDivWarehouseTitle"> <?= $warehouse['name']; ?> </p>
						<p> Voorraad: <?= $warehouse['productStock']; ?> </p>
					</div>
					<div class="col-md-3">
						<i class="productStockDivWarehouseIcon fas fa-shuttle-van"> </i>
					</div>
				</div>
			</div>
					
			<?php } ?>
		</div>
	</div>
</div>