<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateProducts extends AbstractMigration
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
        $table = $this->table('products');
		
		$table->addColumn('name', 'string', [
			'null' => false,
			'length' => 100,
			'default' => null
		]);
		
		$table->addColumn('description', 'text', [
			'null' => false,
			'length' => 500,
			'default' => null
		]);
		
		$table->addColumn('barcode', 'string', [
			'null' => false,
			'length' => 50,
			'default' => null
		]);
		
		$table->addColumn('minimum_stock', 'integer', [
			'null' => false,
			'length' => 3,
			'default' => null
		]);
		
        $table->create();
    }
}
