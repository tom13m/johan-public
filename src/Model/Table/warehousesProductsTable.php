<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class WarehousesProductsTable extends Table
{
    public function initialize(array $config): void {
    	$this->belongsTo('Warehouses', [
			'className' => 'Warehouses',
			'foreignKey' => 'warehouse_id',
			'propertyName' => 'warehouse'
		]);
		
		$this->belongsTo('Products', [
			'className' => 'Products',
			'foreignKey' => 'product_id',
			'propertyName' => 'product'
		]);
    }
}

?>