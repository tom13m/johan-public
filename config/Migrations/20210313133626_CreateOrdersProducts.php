<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateOrdersProducts extends AbstractMigration
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
        $table = $this->table('orders_products');
		
		$table->addColumn('order_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		]);
		
		$table->addColumn('product_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		]);
		
		$table->addColumn('amount', 'integer', [
			'null' => false,
			'length' => 3,
			'default' => 0
		]);
		
        $table->create();
    }
}
