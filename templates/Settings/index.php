<!-- Test file -->
<a href="<?= $this->Url->Build(['controller' => 'Settings', 'action' => 'setFileFormat']); ?>"> <button> push me </button> </a>

<?php if (isset($fileFormats)) {
	Foreach ($fileFormats as $fileFormat) {
		echo $fileFormat->format['barcode'];
	}
} ?>