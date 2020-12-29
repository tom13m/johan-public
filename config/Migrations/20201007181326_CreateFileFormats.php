<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateFileFormats extends AbstractMigration
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
        $table = $this->table('file_formats');
		
		$table->addColumn('supplier_id', 'integer', [
			'null' => true,
			'length' => 6,
			'default' => null
		]);
		
		$table->addColumn('format', 'string', [
			'null' => false,
			'length' => 1000,
			'default' => null
		]);
		
        $table->create();
    }
}
