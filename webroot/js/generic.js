/* All generic Javascript functions inside the system */


/* OnScan initialisation */
onScan.attachTo(document, {
	suffixKeyCodes: [13], // enter-key expected at the end of a scan
	reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
	minLength: 5,
	onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')
		
		let barcodeTarget = document.getElementById('barcodeTarget');
		
		barcodeTarget.value = sCode;
	}
});

/* Function for starting the camera barcode scanner */
function startBarcodeScanner() {
		/* Setting up Quagga Js barcode scanner */
		Quagga.init({
			inputStream : {
				width: '20',
				height: '20',
				name : "Live",
				type : "LiveStream",
				target: document.querySelector('#barcodeScannerCameraTarget')
			},
			decoder : {
			  readers : ["code_128_reader"]
			}
		}, function(err) {
				if (err) {
					console.log(err);
					return
				}

			toggleCameraSection('show');
			Quagga.start();
		});

		/* Setting barcode location */
		Quagga.onDetected(function(result) {	
			Quagga.stop();
			toggleCameraSection('hide');
			
			let barcodeTarget = document.getElementById('barcodeTarget');

			barcodeTarget.value = result.codeResult.code;
			
			Quagga.offDetected();
		});
}
	
/* Function for stopping the camera barcode scanner */
function stopBarcodeScanner() {
	Quagga.stop();
	toggleCameraSection('hide');
	
	Quagga.offDetected();
}

/* Function for toggling camera section */
function toggleCameraSection(toggle) {
	let cameraSection = document.getElementById('barcodeScannerCameraTarget');
	
	if (toggle == 'show') {
		cameraSection.classList.add('active');
	} else if (toggle == 'hide') {
		cameraSection.classList.remove('active');
	}
}


/* Function for retreiving url parameter */
function getParamVariable(variable) {
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if(pair[0] == variable){return pair[1];}
	}
		
	return(false);
}

/* Function for capitalizing first character of a string */
function capitalize(string = null) {
	if (string != null) {
		return string.charAt(0).toUpperCase() + string.slice(1)
	} else {
		return false;
	}
}

/* Function for serizaling form data to an object */
function serializeFormData(form) {
	if (form != null) {
		let data = {};
		
		$.each($(form).serializeArray(), function() {
			data[this.name] = this.value;
		});
		
		return data;
	} else {
		return false;
	}
}

/* Function for toggling an menu */
function toggleMenu(menuId) {
	let menu = document.getElementById(menuId);

	if (menu.classList.contains('active')) {
		menu.classList.remove('active');
	} else {
		menu.classList.add('active');
	}
}

/* Function for setting the active class */
function setActive(c, id) {
	let cs = document.getElementsByClassName(c);
	let active = document.getElementById(id);
	
	$(cs).each(function(i, c) {
		c.classList.remove('active');
	});
	
	active.classList.add('active');
}

/* Function for redirecting */
function redirect(url) {
	window.location.href = url;
}