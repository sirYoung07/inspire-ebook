<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'author_name', 'description', 'price', 'book_path'
    ];

    public function bookable(): MorphTo{
        return $this->morphTo();
    }

    

    public function rentingUsers(){
        return $this->belongsToMany(User::class, 'rented_books', 'book_id' ,'rentable_id'  );
    }
}
