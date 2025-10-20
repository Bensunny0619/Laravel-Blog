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
    Schema::table('comments', function (Blueprint $table) {
        if (!Schema::hasColumn('comments', 'body')) {
            $table->text('body')->after('id');
        }

        if (!Schema::hasColumn('comments', 'user_id')) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        }

        if (!Schema::hasColumn('comments', 'post_id')) {
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
        }
    });
}



    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('comments', function (Blueprint $table) {
        $table->dropColumn('body');
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
        $table->dropForeign(['post_id']);
        $table->dropColumn('post_id');
    });
}

};
