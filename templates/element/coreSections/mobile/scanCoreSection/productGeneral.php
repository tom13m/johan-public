<!-- Product general element -->
<div class="scanContentDiv row">
	<div class="col-12">
		<p> Barcode: <span class="scanProductGeneralDivValue"> <?= $data['product']['barcode']; ?> </span> </p>
	</div>

	<div class="col-12">
		<p> Totale voorraad: <span class="scanProductGeneralDivValue"> <?= $data['product']['totalStock']; ?> </span> </p>
	</div>

	<div class="col-12">
		<?php if ($data['product']['supplier'] != null) { ?>
		<p> Leverancier: <span class="scanProductGeneralDivValue"> <?= $data['product']['supplier']['name']; ?> </span> </p>
		<?php } else { ?>
		<p> Leverancier: <span class="scanProductGeneralDivValue"> Onbekend </span> </p>
		<?php } ?>
	</div>

	<div class="scanProductDescription col-12">
		<p> <?= $data['product']['description']; ?> </p>
	</div>
</div>