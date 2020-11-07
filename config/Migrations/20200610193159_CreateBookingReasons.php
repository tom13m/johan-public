<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBookingReasons extends AbstractMigration
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
        $table = $this->table('booking_reasons');
		
		$table->addColumn('name', 'string', [
			'null' => false,
			'length' => 30,
			'default' => null
		]);
		
        $table->create();
    }
}
