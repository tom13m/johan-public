<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateProductsSets extends AbstractMigration
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
        $table = $this->table('products_sets');
		
		$table->addColumn('datetime', 'datetime', [
			'null' => true,
			'default' => 'CURRENT_TIMESTAMP'
		]);
		
		$table->addColumn('products', 'string', [
			'null' => false,
			'length' => 10000,
			'default' => null
		]);
		
		$table->addColumn('user_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		]);
		
		$table->addColumn('set_key', 'string', [
			'null' => false,
			'length' => 6,
			'default' => null
		]);
		
        $table->create();
    }
}
