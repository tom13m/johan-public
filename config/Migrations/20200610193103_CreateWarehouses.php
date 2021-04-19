<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateWarehouses extends AbstractMigration
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
        $table = $this->table('warehouses');
		
		$table->addColumn('name', 'string', [
			'null' => false,
			'length' => 50,
			'default' => null
		]);
		
		$table->addColumn('description', 'text', [
			'null' => false,
			'length' => 500,
			'default' => null
		]);
		
		$table->addColumn('location_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		]);
		
		$table->addColumn('order_warehouse', 'boolean', [
			'null' => false,
			'default' => false
		]);
		
        $table->create();
    }
}
