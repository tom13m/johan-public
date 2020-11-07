<?php if (!isset($data)) { ?>

<h1> Scan een product </h1>

<div class="scanProductImage">
	Scanplaatje
</div>

<?php } else { ?>

<div class="row coreSectionRow">

	<div class="col-md-7">
		<!-- Product information -->
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<p> <?= $data['product']['name']; ?> </p>			
				</div>
				<div class="row">
					<p> <?= $data['product']['barcode']; ?> </p>
				</div>
				<div class="row">
					<p> <?= $data['product']['description']; ?> </p>
				</div>
				<div class="row">
					<p> Minimum voorraad: </p>
				</div>
			</div>
		</div>

		<!-- Test button for toggling action menu -->
<!--		<button type="button" onclick="toggleMenu('scanActionMenu')"> Acties </button>-->
	</div>

</div>

<?php } ?>