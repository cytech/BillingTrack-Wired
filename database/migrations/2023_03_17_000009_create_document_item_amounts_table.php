<?php

//namespace Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentItemAmountsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'document_item_amounts';

    /**
     * Run the migrations.
     * @table document_item_amounts
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->decimal('subtotal', 20, 4)->default('0.0000');
            $table->decimal('tax_1', 20, 4)->default('0.0000');
            $table->decimal('tax_2', 20, 4)->default('0.0000');
            $table->decimal('tax', 20, 4)->default('0.0000');
            $table->decimal('total', 20, 4)->default('0.0000');
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