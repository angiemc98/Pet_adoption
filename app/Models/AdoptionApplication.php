<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionApplication extends Model
{
        protected $fillable = [
            'pet_id',
            'adopter_id',
            'status',
            'reason_for_adoption',
            'has_experience',
        ];
        public function pet() {
            return $this->belongsTo(Pet::class, 'pet_id');
        }
        public function adopter() {
            return $this->belongsTo(User::class, 'adopter_id');
        }
}
