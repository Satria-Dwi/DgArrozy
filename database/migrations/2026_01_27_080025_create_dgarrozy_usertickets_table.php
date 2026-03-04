<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dgarrozy_usertickets', function (Blueprint $table) {
            $table->id();

            // id_user dari tabel user SIMRS (hasil decrypt nanti)
            $table->string('id_user', 20)->unique();

            // nik pegawai (relasi ke pegawai.nik)
            $table->string('nik', 20);

            // role user ticketing
            $table->enum('role_user', [
                'pembuat',
                'head_section',
                'app_dept',
                'approved',
                'admin'
            ])->default('pembuat');

            $table->timestamps();

            // optional index
            $table->index('nik');
            $table->index('role_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dgarrozy_usertickets');
    }
};
