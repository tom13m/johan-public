/* All Javascript functions for the PC version of the system */


/* Function for processing a given barcode */
function processBarcode() {
	let barcodeTarget = document.getElementById('barcodeTarget');
	let barcode = barcodeTarget.value;

	if (barcode != '') {
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
				let elementPath = 'coreSections_' + section + 'CoreSection';
				let elementId = section + 'CoreSection';

				renderElement(elementPath, data, elementId);
			}
		} else if (data != null) {
			$(openCoreSection).empty();

			let elementPath = 'coreSections_' + section + 'CoreSection';
			let elementId = section + 'CoreSection';

			renderElement(elementPath, data, elementId);
		}

		/* Updating url variables */
		let paramVariables = {
			'coreSection': section
		};

		appendParamVariables(paramVariables);
	}

}

/* Function for changing a visible action menu */
function changeActionMenu(coreSection, actionMenu) {
	/* Receiving action entries and chosen action elements */
	let actions = document.getElementsByClassName(coreSection + '_ActionMenu');
	let chosenActionMenu = document.getElementById(coreSection + '_Action_' + actionMenu);

	/* Hiding all action menus */
	$(actions).each(function(i, action) {
		action.classList.remove('active');
	});

	/* Showing chosen action menu */
	chosenActionMenu.classList.add('active');
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

/* Function for moving product stocks */
function moveProductStock() {
	let form = document.getElementById('moveProductWarehouseForm');

	/* Getting form data */
	let formData = serializeFormData(form);

	/* Moving product stock */
	ajaxRequest('Products', 'moveProductStock', formData, processProductStockMove);

	/* Processing product stock movement to view */
	function processProductStockMove(data) {
		/* Rerendering stock content */
		renderElement('coreSections_scanCoreSection_productStock', data, 'scanProductStockSection');

		/* Rerendering action section */
		renderElement('actionMenus_scanActionMenu_stockMovement', data, 'scan_Action_stockMovement');
		renderElement('actionMenus_scanActionMenu_stockCorrection', data, 'scan_Action_stockCorrection');
	}
}

/* Function for correcting product stock */
function correctProductStock() {
	let form = document.getElementById('correctProductStockForm');
	
	/* Getting form data */
	let formData = serializeFormData(form);
	
	/* Correcting product stock */
	ajaxRequest('Products', 'correctProductStock', formData, processProductStockCorrection);
	
	/* Processing product stock movement to view */
	function processProductStockCorrection(data) {
		/* Rerendering general content */
		renderElement('coreSections_scanCoreSection_productGeneral', data, 'scanProductGeneralSection');
		
		/* Rendering stock content */
		renderElement('coreSections_scanCoreSection_productStock', data, 'scanProductStockSection');
		
		/* Rerendering action sections */
		renderElement('actionMenus_scanActionMenu_stockMovement', data, 'scan_Action_stockMovement');
		renderElement('actionMenus_scanActionMenu_stockCorrection', data, 'scan_Action_stockCorrection');
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
		renderElement('coreSections_scanCoreSection_productGeneral', data, 'scanProductGeneralSection');
		
		/* Rerendering stock content */
		renderElement('coreSections_scanCoreSection_productStock', data, 'scanProductStockSection');
		
		/* Rerendering action sections */
		renderElement('actionMenus_scanActionMenu_editWarehouse', data, 'scan_Action_editWarehouse');
	}
}


/* |||||||||||||||||||| Special functions |||||||||||||||||||| */

/* Binding eventlisteners */
function bindEventListeners() {
	// Main menu rows active function
	let mainMenuRows = document.getElementsByClassName('menuRow');

	$(mainMenuRows).each(function(i, mainMenuRow) {
		mainMenuRow.addEventListener('click', function() { setActive('menuRow', mainMenuRow.id) });
	});
	
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
		
		let menuRow = getParamVariable('coreSection') + 'MenuRow';
		setActive('menuRow', menuRow);
	}
})