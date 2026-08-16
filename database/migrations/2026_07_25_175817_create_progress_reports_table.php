<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_reports', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('work_type');

            $table->unsignedTinyInteger('progress_percent')
                ->default(0);

            $table->text('description')
                ->nullable();

            $table->string('image')
                ->nullable();

            $table->timestamps();


            $table->unique([
                'project_id',
                'work_type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
