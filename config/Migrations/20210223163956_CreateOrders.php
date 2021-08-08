<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateOrders extends AbstractMigration
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
		$table = $this->table('orders');
		
		$table->addColumn('name', 'string', [
			'null' => false,
			'length' => 100,
			'default' => null
		]);
		
		$table->addColumn('order_no', 'integer', [
			'null' => false,
			'length' => 6,
			'default' => null
		]);

		$table->addColumn('datetime', 'datetime', [
			'null' => true,
			'default' => 'CURRENT_TIMESTAMP'
		]);
		
		$table->addColumn('supplier_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		]);

		$table->addColumn('products', 'string', [
			'null' => false,
			'length' => 10000,
			'default' => null
		]);
		
		$table->addColumn('state', 'string', [
			'null' => false,
			'length' => 20,
			'default' => 'draft'
		]);
		
		$table->addColumn('receipt_name', 'string', [
			'null' => false,
			'length' => 30,
			'default' => null
		]);
		
		$table->addColumn('export_type', 'string', [
			'null' => false,
			'length' => 10,
			'default' => 'PDF'
		]);
		
		$table->addColumn('user_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		]);

		$table->create();
	}
}
