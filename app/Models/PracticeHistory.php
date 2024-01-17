<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeHistory extends Model
{
    use HasFactory;

    protected $table = 'practice_history';
    public $timestamps = false;

    public function topic () {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
