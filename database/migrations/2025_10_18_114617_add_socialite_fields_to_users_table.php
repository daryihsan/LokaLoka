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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom untuk Socialite
            $table->string('google_id')->nullable()->after('password_hash');
            // $table->text('avatar_url')->nullable()->after('google_id'); 
            // Tambahkan kolom untuk verifikasi email
            // $table->timestamp('email_verified_at')->nullable()->after('email');

            // Hapus kolom email_verified_at yang sudah ada jika menggunakan migration bawaan Laravel
            // Jika tidak, biarkan saja.
            // Jika Anda sudah menjalankan migrasi bawaan, Anda mungkin perlu menjalankan 
            // php artisan migrate:rollback dan menambahkan ini ke migrasi awal Users.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id']);
        });
    }
};
