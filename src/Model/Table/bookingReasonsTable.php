<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class BookingReasonsTable extends Table
{
    public function initialize(array $config): void {
    	$this->belongsTo('Bookings', [
            'className' => 'Bookings',
			'propertyName' => 'booking'
        ]);
    }
}

?>