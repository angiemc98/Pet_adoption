<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
        use HasFactory;

        protected $fillable = [
            'shelter_id',
            'name',
            'species',
            'breed',
            'age',
            'size',
            'gender',
            'description',
            'status',
            'photo_url',
            'vaccinated',
            'is_sterilized',
        ];
        public function shelter() {
            return $this->belongsTo(User::class, 'shelter_id');
        }
        public function applications() {
            return $this->hasMany(AdoptionApplication::class, 'pet_id');
        }
}

