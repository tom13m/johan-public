<!-- Success row for stocktaking products block -->
<div class="stocktakingProductRow success prepended row">
	<div class="col-md-12">
		<?php if ($data['stocktakingOption'] == 'correction') { ?>
		<p> Voorraadcorrectie uitgevoerd! </p>
		<?php } elseif ($data['stocktakingOption'] == 'movement') { ?>
		<p> Voorraadverplaatsing uitgevoerd! </p>
		<?php } ?>
	</div>
</div>