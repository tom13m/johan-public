/* All generic Javascript functions inside the system */


/* OnScan initialisation */
onScan.attachTo(document, {
	suffixKeyCodes: [13], // enter-key expected at the end of a scan
	//	reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
	minLength: 5,
	onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')

		let barcodeTarget = document.getElementById('barcodeTarget');

		barcodeTarget.value = sCode;

		document.getElementById('barcodeTargetButton').click();
	}
});

/* Function for starting the camera barcode scanner */
function startBarcodeScanner() {
	document.getElementById('barcodeScannerCameraTarget').classList.add('active');

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

		if (mobile = true) {
			setActive('cameraMode', 'cameraModeOn');
		}

		let barcodeTarget = document.getElementById('barcodeTarget');

		barcodeTarget.value = result.codeResult.code;

		document.getElementById('barcodeTargetButton').click();

		Quagga.offDetected();
	});
}

/* Function for stopping the camera barcode scanner */
function stopBarcodeScanner() {
	document.getElementById('barcodeScannerCameraTarget').classList.remove('active');

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

		/* Setting the form data to the new data object */
		$.each($(form).serializeArray(), function() {
			if (this.name.includes('[]')) {
				if (typeof data[this.name] === 'undefined') {
					data[this.name] = [];

					data[this.name].push(this.value);
				} else {
					data[this.name].push(this.value);
				}
			} else {
				data[this.name] = this.value;
			}
		});
		
		/* Removing block brackets */
		$(Object.keys(data)).each(function(i, key) {
			if (key.includes('[]')) {
				oldKey = key;
				newKey = key.replace('[]', '');
				
				data[newKey] = data[oldKey];
				delete data[oldKey];
			}
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

/* Function for generating a random key */
function randomKey(length) {
	let randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	let result = '';

	for (let i = 0; i < length; i++ ) {
		result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
	}

	return result;
}

/* Function for giving an error message */
function giveError(errorTemplate) {
	let elementPath = 'errorTemplates_templates_' + errorTemplate;
	let elementId = 'errorTemplate';

	renderElement(elementPath, null, elementId, complete);

	function complete() {
		/* Showing error message and setting timer for removal */
		let errorTemplate = document.getElementById('errorTemplate');

		errorTemplate.classList.add('active');

		setTimeout(function() {
			errorTemplate.classList.remove('active');
		}, 2000);
	}
}

/* Function for redirecting */
function redirect(url) {
	window.location.href = url;
}