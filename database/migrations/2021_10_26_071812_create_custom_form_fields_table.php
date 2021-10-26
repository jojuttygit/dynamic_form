<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_form_fields', function (Blueprint $table) {
            $table->bigIncrements('custom_form_field_id');
            $table->integer('custom_form_id')->index()->unsigned();
            $table->integer('field_type_id')->index()->unsigned();
            $table->string('label');
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_form_fields');
    }
}
