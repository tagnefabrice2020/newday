<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    use HasFactory;

    public function setter () {
        return $this->belongsTo(User::class);
    }

    public function questions () {
        return $this->hasMany(Question::class);
    }

    public function bundle () {
        return $this->belongsToMany(Bundle::class, 'bundle_topic');
    }
}
