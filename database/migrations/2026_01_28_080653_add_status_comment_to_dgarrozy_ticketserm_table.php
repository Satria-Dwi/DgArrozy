<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dgarrozy_ticketserm', function (Blueprint $table) {
            /* =========================
               COMMENT / CATATAN STATUS
            ========================= */
            $table->text('headsection_comment')->nullable()
                ->after('approved_at')
                ->comment('Komentar dari head section');

            $table->text('appdept_comment')->nullable()
                ->after('headsection_comment')
                ->comment('Komentar dari approval departemen');

            $table->text('approved_comment')->nullable()
                ->after('appdept_comment')
                ->comment('Komentar dari approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dgarrozy_ticketserm', function (Blueprint $table) {
            $table->dropColumn(['open_comment', 'approval_comment']);
        });
    }
};
