<?php if (!isset($data)) { ?>

<div class="scanPlaceholderSection row">
	<div class="col-12 my-auto">
		<div class="row">
			<div class="scanPlaceholderText col-10 offset-1">
				<p> Scan een product </p>
			</div>
		</div>
		<div class="row">
			<div class="col-10 offset-1">
				<?= $this->Html->image('scanPlaceholder.png', ['alt' => 'Scan placeholder', 'class' => 'scanPlaceholderImage']); ?>
			</div>
		</div>
	</div>
</div>

<?php } else { ?>

<div class="coreSectionRow row">
	<div class="col-12">
		<!-- Top bar -->
		<div class="topBar row">
			<div class="topBarTitle col-12"> <?= $data['product']['name']; ?> </div>			
		</div>

		<!-- Product information -->
		<div class="coreSectionContent row">
			<div class="col-12">
				<!-- General information -->
				<span id="scanProductGeneralSection">
					<?= $this->Element('coreSections/mobile/scanCoreSection/productGeneral'); ?>
				</span>

				<!-- Product stock -->
				<span id="scanProductStockSection">
					<?= $this->Element('coreSections/mobile/scanCoreSection/productStock'); ?>
				</span>

				<!-- Temporary -->
				<span id="scanProductTempoSection">
					<?= $this->Element('coreSections/mobile/scanCoreSection/tempo'); ?>
				</span>
			</div>
		</div>

		<!-- Test button for toggling action menu -->
		<!--		<button type="button" onclick="toggleMenu('scanActionMenu')"> Acties </button>-->
	</div>
</div>

<?php } ?>