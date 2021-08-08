<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBookingReasons extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('booking_reasons');
		
		$table->addColumn('name', 'string', [
			'null' => false,
			'length' => 30,
			'default' => null
		]);
		
		$table->addColumn('balance', 'boolean', [
			'null' => false,
			'default' => true
		]);
		
		$table->addColumn('prename', 'string', [
			'null' => false,
			'length' => 20,
			'default' => null
		]);
		
        $table->create();
    }
}
