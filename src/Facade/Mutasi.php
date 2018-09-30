<?php

namespace Lugasdev\Mutasi\Facade;

use Illuminate\Support\Facades\Facade;

class Mutasi extends Facade {

    protected static function getFacadeAccessor(){
        return 'lugasdev-mutasi';
    }
}