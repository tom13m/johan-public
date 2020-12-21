<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class LocationsTable extends Table
{
    public function initialize(array $config): void {
    	$this->belongsTo('Warehouses', [
            'className' => 'Warehouses',
			'foreignKey' => 'warehouse_id',
			'propertyName' => 'warehouse'
        ]);
		
		$this->belongsTo('Suppliers', [
            'className' => 'Suppliers',
			'foreignKey' => 'supplier_id',
			'propertyName' => 'supplier'
        ]);
		
		$this->belongsTo('Customers', [
            'className' => 'Customers',
			'foreignKey' => 'customer_id',
			'propertyName' => 'customer'
        ]);
    }
}

?>