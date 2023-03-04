<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    // public $timestamps = false;

    // Disabling mass assignment
    protected $guarded = [];

    public function profileImage() {

        $imagePath = ($this->image) ? $this->image : 'profile/DefaultProfileImage.jpg';

        return '/storage/' . $imagePath;
    }

    public function followers() {
        
        return $this->belongsToMany(User::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
