<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('phone');
            $table->string('address');
            $table->text('problem_text');
            $table->string('status', 20)->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
