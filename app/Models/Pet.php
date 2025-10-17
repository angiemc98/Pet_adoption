<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{

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

