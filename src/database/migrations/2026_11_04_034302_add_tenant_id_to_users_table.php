<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->index('tenant_id');
        });

        // Satu tenant hanya boleh memiliki
        // satu Kepala Tenant yang belum dihapus.
        DB::statement("
            CREATE UNIQUE INDEX users_one_tenant_head_unique
            ON users (tenant_id)
            WHERE role = 'tenant'
              AND tenant_id IS NOT NULL
              AND deleted_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement(
            'DROP INDEX IF EXISTS users_one_tenant_head_unique'
        );

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropIndex(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
