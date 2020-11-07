<!DOCTYPE HTML>
<html>
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>  </title>

		<!-- CSS files -->
		<?= $this->Html->css('auth.css') ?>
		<?= $this->Html->css('bootstrap.min.css') ?>

		<!-- Javascript files -->
		<?= $this->Html->script('jquery-3.4.0.min.js') ?>
		
		<!-- Online sources -->
    	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	</head>
	<body scroll="no"> 		
		<!-- Main container -->
		<section class="content container-fluid">
			<div class="content row">
				<section class="contentBody col-md-10">
					<!-- Content will be loaded here -->
					<?= $this->fetch('content') ?>
				</section>
			</div>
		</section>
	</body>
	<footer>
		
	</footer>
</html>