/* All Javascript functions for the Mobile version of the system */

function processBarcode() {
	let barcodeTarget = document.getElementById('barcodeTarget');
	let barcode = barcodeTarget.value;

	let data = {};
	data['barcode'] = barcode;

	/* checking coreSection and activating corresponding function */
	let coreSection = getParamVariable('coreSection');

	if (coreSection == false || coreSection == 'scan') {	
		ajaxRequest('main', 'getProductData', data, progressBarcode);

		function progressBarcode(data) {
			/* Opening scan core section and setting data */
			openCoreSection('scan', data);
		}
	} else {

	}
}

/* Function for opening a core section */
function openCoreSection(section = null, data = null) {
	if (section != null) {
		/* Closing current core section and opening chosen core section */
		let coreSections = document.getElementsByClassName('coreSection');

		$(coreSections).each(function(i, coreSection) {
			coreSection.classList.remove('active');
		});

		let openCoreSection = document.getElementById(section + 'CoreSection');

		openCoreSection.classList.add('active');

		/* Rendering content of chosen section if not yet done */
		if ($(openCoreSection).children().length == 0) {
			let elementPath = 'coreSections_mobile_' + section + 'CoreSection';
			let elementId = section + 'CoreSection';

			renderElement(elementPath, data, elementId);
		} else if (data != null) {
			$(openCoreSection).empty();

			let elementPath = 'coreSections_mobile_' + section + 'CoreSection';
			let elementId = section + 'CoreSection';

			renderElement(elementPath, data, elementId);
		}
		
		/* Closing main menu */
		let mainMenu = document.getElementById('mainMenuMobile');
		
		if (mainMenu.classList.contains('active')) {
			toggleMenu('mainMenuMobile');
		}

		/* Updating url variables */
		let paramVariables = {
			'coreSection': section
		};

		appendParamVariables(paramVariables);
	}
}
