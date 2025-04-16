<?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;

   class Task extends Model
   {
       use HasFactory;

       protected $fillable = [
           'user_id',
           'title_hr',
           'title_en',
           'description',
           'study_type',
       ];

       public function user()
       {
           return $this->belongsTo(User::class);
       }

       public function applications()
       {
           return $this->hasMany(TaskApplication::class);
       }
   }