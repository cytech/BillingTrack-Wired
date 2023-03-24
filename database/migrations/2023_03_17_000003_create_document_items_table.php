<?php

//namespace Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentItemsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'document_items';

    /**
     * Run the migrations.
     * @table document_items
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('tax_rate_id');
            $table->unsignedInteger('tax_rate_2_id')->default('0');
            $table->string('resource_table')->nullable()->default(null);
            $table->unsignedInteger('resource_id')->nullable()->default(null);
            $table->tinyInteger('is_tracked')->default('0');
            $table->string('name');
            $table->text('description');
            $table->decimal('quantity', 20, 4)->default('0.0000');
            $table->integer('display_order')->default('0');
            $table->decimal('price', 20, 4)->default('0.0000');

            $table->index(["display_order"], 'document_items_display_order_index');
            $table->softDeletes();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
