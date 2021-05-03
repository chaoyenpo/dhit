<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDqlToWebhookReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webhook_receivers', function (Blueprint $table) {
            $table->json('dql')->default(new Expression('(JSON_OBJECT())'))->after('chat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webhook_receivers', function (Blueprint $table) {
            $table->dropColumn('dql');
        });
    }
}
