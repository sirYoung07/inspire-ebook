<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Code extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'token' , 'expires_at'];

    
    public function codeable(): MorphTo{
        return $this->morphTo();
    }

}
