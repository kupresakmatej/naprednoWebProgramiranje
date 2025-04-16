<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('tasks', function (Blueprint $table) {
               $table->id();
               $table->foreignId('user_id')->constrained()->onDelete('cascade');
               $table->string('title_hr');
               $table->string('title_en');
               $table->text('description');
               $table->enum('study_type', ['stručni', 'preddiplomski', 'diplomski']);
               $table->timestamps();
           });
       }

       public function down(): void
       {
           Schema::dropIfExists('tasks');
       }
   };