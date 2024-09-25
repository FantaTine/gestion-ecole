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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('adresse');
            $table->string('telephone');
            $table->string('fonction');
            $table->string('photo')->nullable();
            $table->enum('statut', ['Bloquer', 'Actif'])->default('Actif');
            $table->enum('role', ['admin', 'coach', 'manager', 'cm', 'apprenant']);
            $table->string('firebase_uid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};