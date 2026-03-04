<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dgarrozy_ticketserm', function (Blueprint $table) {
            $table->id();

            /* =========================
       PEMBUAT TIKET (SIMRS)
    ========================= */
            $table->string('user_nik', 20)
                ->comment('NIK pegawai dari session SIMRS');

            $table->string('user_nama')
                ->comment('Nama pegawai saat membuat tiket');

            $table->string('user_departemen')
                ->nullable();

            /* =========================
       DATA TIKET
    ========================= */
            $table->string('kode_ticket')->unique();
            $table->string('judul');
            $table->text('deskripsi');

            /* =========================
       KATEGORI & PRIORITAS
    ========================= */
            $table->string('kategori');

            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])
                ->default('sedang');

            /* =========================
       STATUS + APPROVAL
    ========================= */
            $table->enum('status', [
                'open',
                'approval_1',
                'approval_2',
                'approved',
                'progress',
                'pending',
                'rejected',
                'closed'
            ])->default('open');

            /* =========================
       APPROVAL INFO
    ========================= */
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->string('rejected_by')->nullable();
            $table->text('rejected_reason')->nullable();

            /* =========================
       SLA
    ========================= */
            $table->integer('sla_hours')->nullable();
            $table->timestamp('sla_deadline')->nullable();
            $table->timestamp('resolved_at')->nullable();

            /* =========================
       ASSIGNMENT
    ========================= */
            $table->string('assigned_to')->nullable()
                ->comment('NIK petugas IT');

            $table->timestamps();

            /* =========================
       INDEX
    ========================= */
            $table->index('user_nik');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dgarrozy_ticketserm');
    }
};
