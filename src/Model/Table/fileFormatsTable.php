<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class FileFormatsTable extends Table
{
    public function initialize(array $config): void {
    	$this->belongsTo('Suppliers', [
            'className' => 'Suppliers',
			'foreignKey' => 'supplier_id',
			'propertyName' => 'supplier'
        ]);
    }
}

?>