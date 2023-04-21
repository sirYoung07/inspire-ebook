<?php

namespace App\Traits;

trait userTrait
{
    protected function getauth(){
        return auth()->user();
    }
}
