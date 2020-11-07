<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
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
        $table = $this->table('users');
		
		$table->addColumn('username', 'string', [
			'null' => false,
			'length' => 30,
			'default' => null
		]);
		
		$table->addColumn('password', 'string', [
			'null' => false,
			'length' => 250,
			'default' => null
		]);
		
		$table->addColumn('email', 'string', [
			'null' => false,
			'length' => 100,
			'default' => null
		]);
		
		$table->addColumn('warehouse_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		])/*->addForeignKey('warehouse_id', 'warehouses', 'id', ['update' => 'cascade', 'delete' => 'set_null'])*/;
		
		$table->addColumn('created', 'datetime', [
			'null' => true,
			'default' => 'CURRENT_TIMESTAMP',
			'default' => null
		]);
		
		$table->addColumn('modified', 'datetime', [
			'null' => true,
			'default' => null
		]);
		
        $table->create();
    }
}
