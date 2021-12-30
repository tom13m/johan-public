<!-- Element for the order send options section -->
<div id="<?= 'orderSendOptions' . $data['order']['id']; ?>" class="orderSendOptions active fullHeight row">
	<div class="col-md-12">
		<?= $this->Form->create(null, ['id' => 'orderSendOptionsForm' . $data['order']['id'], 'class' => 'fullHeight']); ?>

		<?= $this->Form->hidden('state', ['id' => 'orderSendOptionsState' . $data['order']['id'], 'value' => $data['order']['state']]); ?>

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
						<div class="orderSendOptionsActions row">
							<div class="col-md-12">
								<div class="orderSendOptionsButton" onclick="exportOrder(<?= $data['order']['id']; ?>)">
									Exporteer
								</div>
							</div>
						</div>
					</div>
					<div class="orderSendOptionsMail col-md-11">
						<div class="row">
							<div class="col-md-12">
								<p class="orderSendOptionsTitle"> Mailen </p>
							</div>
						</div>
						<div class="orderSendOptionsMailAddress row"> 
							<div class="col-md-12">
								<?php if (isset($data['order']['supplier']['email_address'])) {
									echo 'E-mailadres: ' . $this->Form->control('email_address', ['label' => false, 'value' => $data['order']['supplier']['email_address'], 'class' => 'orderSendOptionsEmailAddressField']);
  								} else {
									echo 'E-mailadres: ' . $this->Form->control('email_address', ['label' => false, 'class' => 'orderSendOptionsEmailAddressField']);
								} ?>
							</div>
						</div>
						<div class="orderSendOptionsAttachments row">
							<div class="col-md-12">
								<p> Bijlagen: </p>
								<div class="row">
									<div class="col-md-1">
										<?= $this->Form->control('attachments[]', ['type' => 'checkbox', 'class' => 'checkbox', 'label' => false, 'value' => 'PDF']); ?>
									</div>
									<div class="col-md-11"> PDF </div>
								</div>
								<div class="row">
									<div class="col-md-1">
										<?= $this->Form->control('attachments[]', ['type' => 'checkbox', 'class' => 'checkbox', 'label' => false, 'value' => 'CSV']); ?>
									</div>
									<div class="col-md-11"> CSV </div>
								</div>
							</div>
						</div>
						<div class="orderSendOptionsActions row">
							<div class="col-md-12">
								<div class="orderSendOptionsButton mail" onclick="mailOrder(<?= $data['order']['id']; ?>)"> 
									Mail
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