<!-- Element for the order send options section -->
<div class="orderSendOptions fullHeight row">
	<div class="col-md-12">
		<?= $this->Form->create(null, ['id' => 'orderSendOptionsForm' . $data['order']['id'], 'class' => 'fullHeight']); ?>

		<div class="fullHeight row">
			<div class="col-md-6">
				<div class="fullHeight row">
					<div class="orderSendOptionsSupplier col-md-11 offset-md-1">
						<div class="row">
							<p class="orderSendOptionsTitle"> Leverancier: <?= $data['order']['supplier']['name']; ?> </p>
						</div>
						<div class="row">
							<p> Leverdatum: </p>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php if (isset($data['customersList'])) { ?>
								<div class="row">
									<p> Klantbestelling: <?= $this->Form->checkbox('customer_order', ['label' => false, 'class' => 'checkbox', 'onclick' => 'toggleOrderSendOptionsCustomer(this.checked)']); ?> </p>
								</div>
								<div class="orderSendOptionsCustomerRow row">
									<?= $this->Form->control('customer_id', ['label' => false, 'options' => $data['customersList'], 'id' => 'orderSendOptionsCustomerField', 'class' => 'orderSendOptionsCustomerField', 'disabled']); ?>
								</div>
								<?php } else { ?>
								<div class="row">
									<p> Klantbestelling </p>
								</div>
								<div class="orderSendOptionsCustomerRow row">
									<?= $this->Form->control('customer_id', ['label' => false, 'placeholder' => 'Geen klanten', 'id' => 'orderSendOptionsCustomerField', 'class' => 'orderSendOptionsCustomerField', 'disabled']); ?>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="fullHeight row">
					<div class="orderSendOptionsReceed col-md-11">
						<div class="row">
							<div class="col-md-12">
								<p class="orderSendOptionsTitle"> Bon </p>
							</div>
						</div>
						<div class="row">
							<?php if ($data['order']['receipt_name'] != null) { ?>
							<div class="col-md-12">
								Bonnaam: <?= $this->Form->control('receipt_name', ['label' => false, 'value' => $data['order']['receipt_name'], 'class' => 'orderSendOptionsReceiptNameField']); ?>
							</div>
							<?php } else { ?>
							<div class="col-md-12">
								Bonnaam: <?= $this->Form->control('receipt_name', ['label' => false, 'value' => $data['order']['supplier']['name'] . ' ' . $data['order']['name'], 
									'class' => 'orderSendOptionsReceiptNameField']); ?>
							</div>
							<?php } ?>
						</div>
						<div class="orderSendOptionsExportType row">
							<div class="col-md-12">
								<?= $this->Form->control('export_type', ['label' => false, 'type' => 'radio', 'options' => [['value' => 'PDF', 'text' => 'PDF formaat'], ['value' => 'CSV', 'text' => 'CSV formaat']], 'class' => 'radioButton', 'default' => $data['order']['export_type']]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<div class="orderSendOptionsButton col-md-10 offset-md-1" onclick="exportOrder(<?= $data['order']['id']; ?>)">
										Exporteer
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?= $this->Form->end(); ?>
	</div>
</div>