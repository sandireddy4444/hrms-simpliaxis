<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_no');
            $table->enum('department', ['HR', 'IT', 'Finance', 'Marketing', 'Operations']);
            $table->string('role');
            $table->date('date_of_joined');
            $table->boolean('is_active')->default(true);
            $table->enum('admin_type', ['Admin', 'SuperAdmin', 'Employee']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};