/* All Javascript functions for the Mobile version of the system */

/* Function for processing a given barcode */
function processBarcode() {
	let barcodeTarget = document.getElementById('barcodeTarget');
	let barcode = barcodeTarget.value;

	let data = {};
	data['barcode'] = barcode;

	/* checking coreSection and activating corresponding function */
	let coreSection = getParamVariable('coreSection');

	if (coreSection == false || coreSection == 'scan') {	
		ajaxRequest('main', 'initScanCoreSection', data, progressBarcode);

		function progressBarcode(data) {
			/* Opening scan core section and setting data */
			openCoreSection('scan', data);
		}
	} else if (coreSection == 'products') {
		document.getElementById('productFormBarcode').value = barcode;
	} else if (coreSection == 'stocktaking') {
		addStocktakingProduct(barcode);
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
			action = 'init' + capitalize(section) + 'CoreSection';
			$data = ajaxRequest('main', action, null, continueOpenCoreSection);

			function continueOpenCoreSection(data) {
				let elementPath = 'coreSections_mobile_' + section + 'CoreSection';
				let elementId = section + 'CoreSection';

				renderElement(elementPath, data, elementId);
			}
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


/* |||||||||||||||||||| Scan core section |||||||||||||||||||| */

/* Function for switching between warehouse options */
function switchProductWarehouse(warehouseId, action) {
	let productWarehousesOptions = document.querySelectorAll('.warehouseOption.' + action);
	let productWarehouseChosenOption = document.getElementById('productWarehouseOption' + capitalize(action) + warehouseId);

	/* Hiding and showing options */
	$(productWarehousesOptions).each(function(i, productWarehouseOption) {
		productWarehouseOption.classList.remove('active');
	});

	if (productWarehouseChosenOption != null) {
		productWarehouseChosenOption.classList.add('active');
	}
}

/* Function for editing a warehouse */
function editWarehouse() {
	let form = document.getElementById('editWarehouseScanActionMenuForm');

	/* Getting form data */
	let formData = serializeFormData(form);

	/* Editing warehouse */
	ajaxRequest('Warehouses', 'edit', formData, processWarehouseEdit);

	function processWarehouseEdit(data) {
		/* Rerendering general content */
		renderElement('coreSections_mobile_scanCoreSection_productGeneral', data, 'scanProductGeneralSection');

		/* Rerendering stock content */
		renderElement('coreSections_mobile_scanCoreSection_productStock', data, 'scanProductStockSection');

		/* Rerendering stock content */
		renderElement('coreSections_mobile_scanCoreSection_tempo', data, 'scanProductTempoSection');
	}
}


/* |||||||||||||||||||| Special functions |||||||||||||||||||| */

/* Binding eventlisteners */
function bindEventListeners() {
	// Barcode target field
	let barcodeTarget = document.getElementById('barcodeTarget');

	barcodeTarget.addEventListener('keyup', function(event) {
		if (event.keyCode === 13) {
			document.getElementById('barcodeTargetButton').click();
		}
	});
}

window.addEventListener('DOMContentLoaded', (event) => {
	bindEventListeners();
});


/* If page is loaded fire this event */
$( window ).on( "load", function() {
	// If coresection param is set then open that coresection
	if (getParamVariable('coreSection') != false) {
		openCoreSection(getParamVariable('coreSection'));

		//		let menuBlock = getParamVariable('coreSection') + 'MenuRow';
		//		setActive('menuRow', menuRow);
	}
})