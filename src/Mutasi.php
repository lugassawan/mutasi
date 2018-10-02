<?php

namespace Lugasdev\Mutasi;

use Illuminate\Support\Facades\Session;
use Lugasdev\Mutasi\Support\DimasEngine as DimasEngine;

class Mutasi {
    
    use DimasEngine;
    
    //for test config
    public function saySomething(){
        return config('mutasi.license');
    }

    /*
        function start here
    */

    //Check mutation by bank name
    public function cekMutasi($bank, $s = 0)
    {
        if($s){
            $ss = $s;
            $data = $this->c($bank);
            $dataSS = $this->c($ss);
            //$s = c($bank)[$bank]; 
            $s = $data[$bank];
            return array(
                $bank => $s, 
                $ss => $dataSS[$ss]
            ); 
        }else{
            //return c( $bank );
            $data = $this->c($bank);
            return $data;
        }
    }

    //Check mutation by passing auth using method get
    public function cekMutasiAuth($auth, $bank, $s = 0)
    {
        //store session to get data
        Session::put($auth);
        $data = $this->cekMutasi($bank); 
        
        //delete session after get data
        Session::flush();
        return $data;
    }
}