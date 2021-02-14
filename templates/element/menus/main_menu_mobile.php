<!-- Element is imported in main mobile layout file for main menu -->
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<p class="mainMenuTitle"> Menu </p>
		</div>
	</div>

	<!-- Menu options for scan and products core sections -->
	<div class="menuRow row">
		<div class="col-5 offset-1">
			<div class="row">
				<div id="scanMenuBlock" class="menuBlock col-10 offset-1" onclick="openCoreSection('scan')">
					<i class="fas fa-barcode"> </i>
					<p class="menuBlockTitle"> Scan </p>
				</div>
			</div>
		</div>


		<div class="col-5">
			<div class="row">
				<div id="productsMenuBlock" class="menuBlock col-10 offset-1">
					<i class="fas fa-cubes"> </i>
					<p class="menuBlockTitle"> Producten </p>
				</div>
			</div>
		</div>
	</div>

	<!-- Menu options for stocktaking and _____ core sections -->
	<div class="menuRow row">
		<div class="col-5 offset-1">
			<div class="row">
				<div id="stocktakingMenuBlock" class="menuBlock col-10 offset-1"  onclick="openCoreSection('stocktaking')">
					<i class="fas fa-truck-loading"> </i>
					<p class="menuBlockTitle"> Inventarisatie </p>
				</div>
			</div>
		</div>
		<div class="col-5">
			<div class="row">
				<div class="menuBlock col-10 offset-1" onclick="redirect('<?= $this->Url->Build(['controller' => 'users', 'action' => 'logout']); ?>')">
					<i class="fas fa-sign-out-alt"> </i>
					<p class="menuBlockTitle"> Log-out </p>
				</div>
			</div>
		</div>
	</div>

	<!-- Menu options for ____ and _____ core sections -->
	<div class="menuRow row">
		<div class="col-5 offset-1">
			<div class="row">
				<div class="menuBlock col-10 offset-1">

				</div>
			</div>
		</div>
		<div class="col-5">
			<div class="row">
				<div class="menuBlock col-10 offset-1">

				</div>
			</div>
		</div>
	</div>
</div>