<?php

//namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $tableName = 'documents';

    /**
     * Run the migrations.
     *
     * @table documents
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('document_type')->nullable()->default(null);
            $table->unsignedInteger('document_id');
            $table->date('document_date');
            $table->unsignedInteger('workorder_id')->nullable()->default(null);
            $table->unsignedInteger('invoice_id')->nullable()->default(null);
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('company_profile_id')->nullable()->default(null);
            $table->unsignedInteger('group_id')->nullable()->default(null);
            $table->integer('document_status_id');
            $table->date('action_date');
            $table->string('number');
            $table->text('footer')->nullable()->default(null);
            $table->string('url_key');
            $table->string('currency_code')->nullable()->default(null);
            $table->decimal('exchange_rate', 10, 7)->default('1.0000000');
            $table->text('terms')->nullable()->default(null);
            $table->string('template')->nullable()->default(null);
            $table->string('summary')->nullable()->default(null);
            $table->tinyInteger('viewed')->default('0');
            $table->decimal('discount', 15, 2)->default('0.00');
            $table->date('job_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->tinyInteger('will_call')->default('0');
            $table->integer('recurring_frequency')->nullable();
            $table->integer('recurring_period')->nullable();
            $table->date('next_date')->nullable();
            $table->date('stop_date')->nullable();

            $table->index(['user_id'], 'documents_user_id_index');

            $table->index(['group_id'], 'documents_group_id_index');

            $table->index(['number'], 'documents_number_index');

            $table->index(['company_profile_id'], 'documents_company_profile_id_index');

            $table->index(['client_id'], 'documents_client_id_index');
            $table->softDeletes();
            $table->nullableTimestamps();

            $table->foreign('company_profile_id', 'documents_company_profile_id_index')
                ->references('id')->on('company_profiles')
                ->onDelete('set null')
                ->onUpdate('restrict');

            $table->foreign('group_id', 'documents_group_id_index')
                ->references('id')->on('groups')
                ->onDelete('set null')
                ->onUpdate('restrict');

            $table->foreign('user_id', 'documents_user_id_index')
                ->references('id')->on('users')
                ->onDelete('set null')
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
