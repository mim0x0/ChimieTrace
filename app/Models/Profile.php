<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'status', 'score', 'profile_pic'
    ];

    public function profilePic() {
        $imagePath = ($this->profile_pic) ? $this->profile_pic : 'profile/rFnWOcUFK9TSL6QAYsU6tI9jDutlaYXpOQS9zo2C.jpg';

        return '/storage/' . $imagePath;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
