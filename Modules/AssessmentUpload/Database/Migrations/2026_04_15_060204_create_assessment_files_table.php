<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')
                  ->constrained('assessments')
                  ->cascadeOnDelete();
            $table->string('original_name');
            $table->string('storage_path');
            $table->string('disk')->default('local');;
            $table->string('mime_type');
            $table->unsignedBigInteger('size_bytes');
            $table->enum('status', ['uploaded','pending_removal', 'removed'])->default('uploaded');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_files');
    }
};
