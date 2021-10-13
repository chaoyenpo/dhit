<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id');
            $table->string('vendor')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('price', 64, 0)->nullable();
            $table->string('name');
            $table->text('remark')->nullable();
            $table->timestamp('purchase_at')->nullable();
            $table->timestamp('certificate_expired_at');
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
        Schema::dropIfExists('certs');
    }
}
