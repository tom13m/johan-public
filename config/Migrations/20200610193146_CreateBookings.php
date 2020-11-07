<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBookings extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('bookings');
		
		$table->addColumn('product_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('product_id', 'products', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
		$table->addColumn('datetime', 'datetime', [
			'null' => true,
			'default' => 'CURRENT_TIMESTAMP',
			'default' => null
		]);
		
		$table->addColumn('amount', 'integer', [
			'null' => false,
			'length' => 3,
			'default' => null
		]);
		
		$table->addColumn('from_location_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('location_id', 'locations', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
		$table->addColumn('to_location_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('location_id', 'locations', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
		$table->addColumn('booking_reason_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('booking_reason_id', 'booking_reasons', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
        $table->create();
    }
}
