<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class ProductsTable extends Table
{
    public function initialize(array $config): void {
    	$this->belongsTo('Suppliers', [
            'className' => 'Suppliers',
			'foreignKey' => 'supplier_id',
			'propertyName' => 'supplier'
        ]);
		
		$this->belongsToMany('Warehouses', [
            'through' => 'WarehousesProducts'
        ]);
    }
}

?>