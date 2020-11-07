<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateWarehousesProducts extends AbstractMigration
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
        $table = $this->table('warehouses_products');
		
		$table->addColumn('warehouse_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])->addForeignKey('warehouse_id', 'warehouses', 'id', ['update' => 'cascade', 'delete' => 'set_null']);
		
		$table->addColumn('product_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('product_id', 'products', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
		$table->addColumn('stock', 'integer', [
			'null' => false,
			'length' => 3,
			'default' => null
		]);
		
        $table->create();
    }
}
