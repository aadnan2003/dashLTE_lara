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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 45);
            $table->mediumText('info');

            /**
             * Foreign Key - Relation
             * Example: Categories <=> Users
             *          - Users => Categories: one-to-many
             *          - Categories => Users: one-to-one (inverse)
             * -Create Foreign Key:
             *  1) Create a new column with Same data type as Primary Key
             *  2) Add FOREIGN KEY INDEX to the created column
             */

            $table->foreignId('user_id');
            $table->foreign('user_id')->on('users')->references('id');

            // $table->foreignId('user_id')->constrained();

            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
