<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgarrozyRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('dgarrozy_role', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // admin, user, dokter
            $table->string('name', 100);          // Administrator, User, Dokter
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dgarrozy_role');
    }
}
