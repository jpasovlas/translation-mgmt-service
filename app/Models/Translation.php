<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;

class Translation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['key','locale','value','notes'];

    /**
     * Get the tags for the translation.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_translation');
    }
}
