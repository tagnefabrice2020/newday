<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'bundle_topic');
    }
}
