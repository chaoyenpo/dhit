<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertiesToDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            // 產品/人員
            // 提交人員
            // 註冊NS位址
            // 域名商
            $table->string('product')->nullable()->after('certificate_expired_at');
            $table->string('submit')->nullable()->after('product');
            $table->string('dns')->nullable()->after('submit');
            $table->string('vendor')->nullable()->after('dns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('product');
            $table->dropColumn('submit');
            $table->dropColumn('dns');
            $table->dropColumn('vendor');
        });
    }
}
