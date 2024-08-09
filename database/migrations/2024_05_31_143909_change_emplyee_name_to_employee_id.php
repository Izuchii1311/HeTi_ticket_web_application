<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->renameColumn('employee_name','employee_id');

        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->integer('employee_id')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->renameColumn('employee_id', 'employee_name');

        });
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('employee_name')->change();
        });
    }
};
