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
    Schema::create('acl_roles_permissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('role_id')->index()->constrained()->references('id')->on('acl_roles')->cascadeOnDelete();
        $table->foreignId('permission_id')->index()->constrained()->references('id')->on('acl_permissions')->cascadeOnDelete();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
