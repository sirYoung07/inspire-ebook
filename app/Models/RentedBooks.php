<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPUnit\Framework\Attributes\IgnoreFunctionForCodeCoverage;

class RentedBooks extends Model
{
    use HasFactory;
   // use SoftDeletes;

    public function rentable(): MorphTo{
        return $this->morphTo();
    }
    
    public function books(): MorphMany{
        return $this->morphMany(Book::class, 'bookable');
    }


}
