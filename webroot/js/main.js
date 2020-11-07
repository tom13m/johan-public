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
			ajaxRequest('main', 'getProductData', data, progressBarcode);

			function progressBarcode(data) {
				/* Opening scan core section and setting data */
				openCoreSection('scan', data);
			}
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
			let elementPath = 'coreSections_' + section + 'CoreSection';
			let elementId = section + 'CoreSection';

			renderElement(elementPath, data, elementId);
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
	let productWarehousesOptions = document.querySelectorAll('.productWarehouseOption.' + action);
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
		renderElement('actionMenus_scanActionMenu_stockMovement', data, 'scan_Action_stockMovement')
	}
}


/* Binding eventlisteners */
function bindEventListeners() {
	// Main menu rows active function
	let mainMenuRows = document.getElementsByClassName('menuRow');
	
	$(mainMenuRows).each(function(i, mainMenuRow) {
		mainMenuRow.addEventListener('click', function() { setActive('menuRow', mainMenuRow.id) });
	});
}

window.addEventListener('DOMContentLoaded', (event) => {
    bindEventListeners();
});