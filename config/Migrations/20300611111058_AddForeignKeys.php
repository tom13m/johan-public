<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddForeignKeys extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
		/* General tables */
		
		/* Users */
		$table = $this->table('users');
		
		$table->addForeignKey('warehouse_id', 'warehouses', 'id', ['update' => 'cascade', 'delete' => 'set_null']);
		
		$table->save();
		
		
		/* Customers */
		$table = $this->table('customers');
		
		$table->addForeignKey('location_id', 'locations', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		$table->save();
		
		
		/* Suppliers */
		$table = $this->table('suppliers');
		
		$table->addForeignKey('location_id', 'locations', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		$table->save();
		
		
		/* Warehouses */
		$table = $this->table('warehouses');
		
		$table->addForeignKey('location_id', 'locations', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		$table->save();
		
		
		/* Products */
		$table = $this->table('products');
		
		$table->addForeignKey('supplier_id', 'suppliers', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		$table->save();
		
		
		/* Locations */
		$table = $this->table('locations');
		
		$table->addForeignKey('customer_id', 'customers', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		$table->addForeignKey('supplier_id', 'suppliers', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		$table->addForeignKey('warehouse_id', 'warehouses', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		$table->save();
		
		
		/* Bookings */
		$table = $this->table('bookings');
		
		$table->addForeignKey('product_id', 'products', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		$table->addForeignKey('from_location_id', 'locations', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		$table->addForeignKey('to_location_id', 'locations', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		$table->addForeignKey('booking_reason_id', 'booking_reasons', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		$table->save();
		
		
		/* File formats */
		$table = $this->table('file_formats');
		
		$table->addForeignKey('supplier_id', 'suppliers', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		
		/* Products sets */
		$table = $this->table('products_sets');
		
		$table->addForeignKey('user_id', 'users', 'id', ['update' => 'cascade', 'delete' => 'set_null']);
		
		
		/* Combo tables */
		
		/* WarehousesProducts */
		$table = $this->table('warehouses_products');
		
		$table->addForeignKey('warehouse_id', 'warehouses', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		$table->addForeignKey('product_id', 'products', 'id', ['update' => 'cascade', 'delete' => 'cascade']);
		
		$table->save();
    }
}
