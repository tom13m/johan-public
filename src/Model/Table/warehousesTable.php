<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class WarehousesTable extends Table
{
    public function initialize(array $config): void {
    	$this->belongsToMany('Products', [
            'through' => 'WarehousesProducts'
        ]);
		
		$this->hasOne('Locations');
    }
}

?>