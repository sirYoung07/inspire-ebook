<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name', 'description', 'price', 'book_path'
    ];

    public function bookable(): MorphTo{
        return $this->morphTo();
    }
}
