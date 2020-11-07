<!DOCTYPE HTML>
<html>
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title> Test </title>

		<!-- CSS files -->
		<?= $this->Html->css('style.css') ?>
		<?= $this->Html->css('bootstrap.min.css') ?>

		<!-- Javascript files -->
		<?= $this->Html->script('jquery-3.4.0.min.js') ?>
		<?= $this->Html->script('bootstrap.min.js') ?>
		<?= $this->Html->script('popper.min.js') ?>
		<?= $this->Html->script('quagga.min.js') ?>
	</head>
	<body> 
		<!-- Content from page -->
		<?= $this->Flash->render() ?>
		<?= $this->fetch('content') ?>
	</body>
	<footer>
		
	</footer>
</html>