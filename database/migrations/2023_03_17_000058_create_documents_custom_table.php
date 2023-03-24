<?php

//namespace Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsCustomTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'documents_custom';

    /**
     * Run the migrations.
     * @table documents_custom
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('document_id');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('document_id', 'documents_custom_document_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
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
