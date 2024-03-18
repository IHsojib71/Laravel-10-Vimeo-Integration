<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Videos extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['folder'];


    public function folder() : BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }
}
