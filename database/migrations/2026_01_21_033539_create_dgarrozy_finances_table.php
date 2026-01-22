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
        Schema::create('dgarrozy_finances', function (Blueprint $table) {
            $table->id();

            $table->string('nama_perencanaan', 150);
            $table->decimal('total_perencanaan', 15, 2)->default(0);
            $table->text('deskripsi')->nullable();

            $table->decimal('modal_awal', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('modal_akhir', 15, 2)->default(0);

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dgarrozy_finances');
    }
};
