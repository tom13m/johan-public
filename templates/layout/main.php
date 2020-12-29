<!DOCTYPE HTML>
<html>
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title> Main </title>

		<!-- CSS files -->
		<?= $this->Html->css('style.css') ?>
		<?= $this->Html->css('bootstrap.min.css') ?>

		<!-- Javascript files -->
		<?= $this->Html->script('jquery-3.4.0.min.js') ?>
		<?= $this->Html->script('jquery.csv.min.js') ?>
		<?= $this->Html->script('bootstrap.min.js') ?>
		<?= $this->Html->script('popper.min.js') ?>
		<?= $this->Html->script('quagga.min.js') ?>
		<?= $this->Html->script('onscan.min.js') ?>
		<?= $this->Html->script('jszip.js') ?>
		<?= $this->Html->script('xlsx.js') ?>
		<?= $this->Html->script('main.js') ?>
		<?= $this->Html->script('generic.js') ?>
		
		<!-- Online sources -->
    	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	</head>
	<body scroll="no"> 
		<div id="barcodeScannerCameraTarget" class="barcodeScannerCameraSection">
			
		</div>
		
		<!-- Main container -->
		<section class="content container-fluid">
			<div class="content row">
				<!-- Main menu -->
				<section class="mainMenu col-md-2">
					<?= $this->Element('menus/main_menu'); ?>
				</section>

				<section id="contentBody" class="contentBody col-md-10">
					<!-- Content will be loaded here -->
					<?= $this->Element('errorTemplates/errorTemplate'); ?>
					
					<?= $this->fetch('content') ?>
				</section>
			</div>
		</section>
	</body>
	<footer>
		
	</footer>
</html>

<script>
	$.ajaxSetup({
        headers: {
            'X-CSRF-Token': <?= json_encode($this->request->getAttribute('csrfToken')); ?>
        }
    });
		
	/* Function for rendering an element */
	function renderElement(element, data = null, id, callback) {	
		let path = "<?= $this->Url->Build(['controlller' => 'main', 'action' => 'renderElement']); ?>" + "/" + element;
		
		$('#' + id).load(path, {
			data: data
		}, function() {
			if (callback != null) {
				callback();
			}
		});
	}
	
	function appendParamVariables(data = null) {
		let urlVariables = '?';
		
		if (data != null) {
			$.each(data, function(key, value) {	
				urlVariables += key + '=' + value;
			});
			
			window.history.pushState("main", "main", "<?php $this->Url->Build(['controller' => 'main', 'action' => 'index']); ?>" + urlVariables);
		} else {
			
		}
	}
	
	/* Function that creates an ajax request */
	function ajaxRequest(controller, action, data = null, callback = null) {
		console.log(data);
		
		$.ajax({
			url : "<?=$this->Url->build(['controller' => '']);?>" + "/" + controller + "/" + action,
			type : 'POST',
			data : {
				'data': data
			},
			dataType :'json',
			success : function(dataArray) {    
				let response = dataArray.response;
				
//				console.log(response);
				
				if (typeof response.data !== 'undefined') {
					data = response.data;
					
					if (callback != null) {
						callback(data);
					}
				} else if (response.success == 0) {
					data = null;
					
					giveError(response.errorTemplate);
				} else {
					data = null;
					
					if (callback != null) {
						callback(data);
					}
				}
			},
			error : function(request,error)
			{
				console.error(error);
			}
		});
	}
</script>