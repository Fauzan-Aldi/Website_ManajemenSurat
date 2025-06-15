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
        Schema::create('encrypted_files', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');    // nama file asli
            $table->string('encrypted_name');   // nama file terenkripsi
            $table->string('path');             // path file di storage
            $table->timestamps();               // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encrypted_files');
    }
};
