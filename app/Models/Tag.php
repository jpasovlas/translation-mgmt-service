<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Translation;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function translations()
    {
        return $this->belongsToMany(Translation::class, 'tag_translation');
    }
}
