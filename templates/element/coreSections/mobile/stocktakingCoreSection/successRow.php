<!-- Product row for stocktaking products block -->
<div class="stocktakingProductRow success prepended row">
	<div class="col-10 offset-1">
		<?php if ($data['stocktakingOption'] == 'correction') { ?>
		<p> Voorraadcorrectie uitgevoerd! </p>
		<?php } elseif ($data['stocktakingOption'] == 'movement') { ?>
		<p> Voorraadverplaatsing uitgevoerd! </p>
		<?php } ?>
	</div>
</div>