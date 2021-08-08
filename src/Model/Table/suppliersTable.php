<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class SuppliersTable extends Table
{
    public function initialize(array $config): void {
    	$this->hasMany('Products');
    	$this->hasMany('Orders');
		
		$this->hasOne('Locations');
		$this->hasOne('FileFormats');
    }
}

?>