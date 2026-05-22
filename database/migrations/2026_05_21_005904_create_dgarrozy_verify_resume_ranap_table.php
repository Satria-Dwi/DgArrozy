<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dgarrozy_verify_resume_ranap', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('no_rawat', 20);
            $table->string('no_rm', 15);
            $table->boolean('verified')->default(false);

            $table->dateTime('verify_date')
                ->nullable();

            // nama user login yg melakukan verify
            $table->string('verified_by', 100)
                ->nullable();

            $table->text('comment')
                ->nullable();

            $table->timestamps();

            // index
            $table->index('no_rawat');
            $table->index('no_rm');
            $table->index('verified_by');

            // 1 no_rawat hanya 1 verifikasi
            $table->unique('no_rawat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dgarrozy_verify_resume_ranap');
    }
};