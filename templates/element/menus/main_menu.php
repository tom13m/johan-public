<!-- Element is imported in main layout file for main menu -->
<div class="menuRow row" id="scanMenuRow" onclick="openCoreSection('scan')">
	<div class="menuItem">
		Scannen
	</div>
</div>
<div class="menuRow row" id="productsMenuRow" onclick="openCoreSection('products')">
	<div class="menuItem">
		Producten
	</div>
</div>
<div class="menuRow row" id="stocktakingMenuRow" onclick="openCoreSection('stocktaking')">
	<div class="menuItem">
		Inventarisatie
	</div>
</div>
<div class="menuRow row" id="logoffMenuRow" onclick="redirect('<?= $this->Url->Build(['controller' => 'users', 'action' => 'logout']); ?>')">
	<div class="menuItem">
		Log-out
	</div>
</div> 


<div class="barcodeScanRow row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6" onclick="stopBarcodeScanner()">
				Scan
			</div>
			<div class="col-md-6" onclick="startBarcodeScanner()">
				Camera
			</div>
		</div>
		<div class="row">
			<input type="text" id="barcodeTarget" class="barcodeScanField col-md-10", autocomplete="off">
			
			<button type="button" id="barcodeTargetButton" class="barcodeSubmissionButton col-md-2" onclick="processBarcode()"> <i class="fas fa-chevron-right"> </i> </button>
		</div>
	</div>
</div>