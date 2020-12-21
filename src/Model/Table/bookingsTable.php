<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class BookingsTable extends Table
{
    public function initialize(array $config): void {
    	$this->belongsTo('BookingReasons', [
            'className' => 'BookingReasons',
			'foreignKey' => 'booking_reason_id',
			'propertyName' => 'booking_reason'
        ]);
    }
}

?>