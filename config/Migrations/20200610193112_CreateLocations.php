<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLocations extends AbstractMigration
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
        $table = $this->table('locations');
		
		$table->addColumn('name', 'string', [
			'null' => false,
			'length' => 100,
			'default' => null
		]);
		
		$table->addColumn('customer_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('customer_id', 'customers', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
		$table->addColumn('warehouse_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('warehouse_id', 'warehouses', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
		$table->addColumn('supplier_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('supplier_id', 'suppliers', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
        $table->create();
    }
}
