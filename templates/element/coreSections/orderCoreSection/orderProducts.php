<!-- Element for a single order products section -->
<div id="<?= 'orderProducts' . $data['order']['id']; ?>" class="orderProducts active row">
	<?= $this->Form->create(null, ['id' => 'orderProductsForm' . $data['order']['id']]); ?>
	
	<div id="<?= 'orderProductRows' . $data['order']['id']; ?>" class="col-md-12">
		<!-- For each product create a row -->
		<?php 	
		if ($data['order']['products'] != '') {
			Foreach($data['order']['products'] as $orderProduct) {
				$singleData = [];
				$singleData['orderProduct'] = $orderProduct;
				
				echo $this->Element('coreSections/orderCoreSection/orderProductRow', ['data' => $singleData]);
			} 
		} else {
			echo 'Nog geen producten';
		}
		?>
	</div>
	
	<?= $this->Form->end(); ?>
</div>