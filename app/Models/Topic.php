<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    public function setter()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function bundle()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_topic');
    }

    public function practiceHistory()
    {
        return $this->hasMany(PracticeHistory::class, 'topic_id');
    }
}
