<!-- Product general element -->
<div class="scanProductGeneralDiv row">
	<div class="col-md-4">
		<div class="row">
			<p> Totale voorraad: <span class="scanProductGeneralDivValue"> <?= $data['product']['totalStock']; ?> </span> </p>
		</div>
		<div class="row">
			<p> Leverancier: <span class="scanProductGeneralDivValue"> <?= $data['product']['supplier']['name']; ?> </span> </p>
		</div>
	</div>
	<div class="col-md-8">
		<p> <?= $data['product']['description']; ?> </p>
	</div>
</div>