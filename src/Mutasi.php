<?php

namespace Lugasdev\Mutasi;

class Mutasi {
    
    public function saySomething(){
        return config('mutasi.message');
    }
}