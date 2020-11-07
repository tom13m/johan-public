<?= $this->Form->create(); ?>

<div class="row">
	<div class="col-md-12">
		<p> Login </p>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<?= $this->Form->control('username', ['label' => false]); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= $this->Form->control('password', ['label' => false, 'type' => 'password']); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $this->Form->submit(); ?>
	</div>
</div>

<?= $this->Form->end(); ?>