<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('admin_type', ['Admin', 'SuperAdmin', 'Employee', 'HR Manager', 'Department Manager'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('admin_type', ['Admin', 'SuperAdmin', 'Employee'])->change();
        });
    }
};