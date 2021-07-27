<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyExpiredAtToDomainsTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->renameColumn('expired_at', 'domain_expired_at');
            $table->timestamp('certificate_expired_at')->nullable()->after('expired_at');
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
            $table->renameColumn('domain_expired_at', 'expired_at');
            $table->dropColumn('certificate_expired_at');
        });
    }
}
