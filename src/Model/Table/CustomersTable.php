<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class CustomersTable extends Table
{
    public function initialize(array $config): void {
    	$this->hasOne('Locations');
    }
}

?>