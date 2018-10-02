<?php

namespace Lugasdev\Mutasi\Support;

use Illuminate\Support\Facades\Session;
use Lugasdev\Mutasi\Support\DimasLibR32 as DimasLibR32;
use Lugasdev\Mutasi\Support\DimasLibR64 as DimasLibR64;

trait DimasEngine {
    
    use DimasLibR32, DimasLibR64;

    public $th = 0;

    public function throwError( $msg, $name = 'engine' ) {
        $this->th = 1;
        $this->th = array( $name=>array( 'status'=>'error', 'results'=>array( 'errorMsg'=>$msg ) ) );
    }

    public function goCryptRequest($value)
    {
        $salt = 'A1M4sW1d2ND24WCnAA2W122MnAR82ABN';
    
        $key = $salt;
        $ren = "29128652421382";
    
        //$data = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $value . md5( $value ) , MCRYPT_MODE_ECB, $ren );////////masalah untuk BNI dan BRI
        $data = openssl_encrypt($value . md5( $value ), 'aes-256-ecb', $key, OPENSSL_RAW_DATA);
        return base64_encode($data);
    }

    public function lns( $s = 0 )
    {
        $config = app('config')->get('mutasi');

        if(!is_null($config)){
            $banks = $config['banks'];
            $w = array();

            /*
                check bank has set
            */
            if(isset($banks['bca'])){
                if($s==1 || $s==0){
                    $w['a'] = $banks['bca'];

                    if (Session::has(['user', 'pass'])) {
                        $session = Session::all();
                        $w['a']['username'] = $session['user'];
                        $w['a']['password'] = $session['pass'];
                    }
                }
            }

            if(isset($banks['mandiri'])){
                if($s==2 || $s==0){
                    $w['b'] = $banks['mandiri'];

                    if (Session::has(['user', 'pass'])) {
                        $session = Session::all();
                        $w['b']['username'] = $session['user'];
                        $w['b']['password'] = $session['pass'];
                    }
                }
            }

            if(isset($banks['bni'])){
                if($s==3 || $s==0){
                    $w['c'] = $banks['bni'];

                    if (Session::has(['user', 'pass'])) {
                        $session = Session::all();
                        $w['c']['username'] = $session['user'];
                        $w['c']['password'] = $session['pass'];
                    }
                }
            }

            if(isset($banks['bri'])){
                if($s==4 || $s==0){
                    $w['d'] = $banks['bri'];

                    if (Session::has(['user', 'pass'])) {
                        $session = Session::all();
                        $w['d']['username'] = $session['user'];
                        $w['d']['password'] = $session['pass'];
                    }
                }
            }

            $w['dimasLicense'] = $config['license'];

            return $w;
        }else{
            throwError( 'Config tidak ditemukan / Lokasi config salah.' );
        }
    }

    public function rlcn($n)
    {
        $n = base64_decode($n);
        $s = @unserialize($n);
        if ( !is_array( $s ) ) {
            die( 'License error.' );
        }
        return $s;
    }

    public function a( $n ) {
        $a = strstr( $n, 'var s = document.createElement(\'script\'), attrs = { src: (window.location.protocol ==', 1 );
        $a = strstr( $a, 'function getCurNum(){' );$b = array( 'return "', 'function getCurNum(){', '";', '}', '{', '(function()' );
        $b = str_replace( $b, '', $a );
        $s = trim( $b );
    
        return $s;
    }

    public function b( $var1=0, $var2=0, $pool ) {
        $temp1 = strpos( $pool, $var1 )+strlen( $var1 );
        $result = substr( $pool, $temp1, strlen( $pool ) );
        $dd=strpos( $result, $var2 );
        if ( $dd == 0 ) {
            $dd = strlen( $result );
        }
    
        return substr( $result, 0, $dd );
    }

    public function c( $b = 0 ) {        
        $_this = (new self);
        switch ( $b ) {
            case 'BCA':
                $rslt = array();
                $blnce = 0;
                $rslts = array();
                $w = $_this->lns(1);
                $rslt = $this->getLibR32($w);

                $rslts['status'] = 'success';
                $rslts['results']['bank'] = $_this->d( 'QkNB' );
                $rslts['results']['balance'] = $blnce;

                if ( empty( array_filter($rslt) ) ) {
                    $_this->throwError( 'Terjadi error saat cek mutasi.', $_this->d( 'TWFuZGlyaQ==' ) );
                } else {
                    foreach ( $rslt as $key => $val ) {
                        $rslts['results']['data'][$key]['description'] = $val['description'];
                        $rslts['results']['data'][$key]['type'] = $val['type'];
                        $rslts['results']['data'][$key]['total'] = $val['total'];
                        $rslts['results']['data'][$key]['balanceposition'] = $val['bal'];
                        if ( $val['date']=='PEND' ) { $val['date'] = date( 'd/m' ); } $date=explode( '-', str_replace( '/', '-', $val['date'].'/'.date( 'Y' ) ) );
                        $rslts['results']['data'][$key]['date'] = $date[2].'-'.$date[1].'-'.$date[0];
                        $rslts['results']['data'][$key]['checkdate'] = date( 'Y-m-d' );
                        $rslts['results']['data'][$key]['checkdatetime'] = date( 'Y-m-d H:i:s' );
                    }
                    return array( $_this->d( 'QkNB' )=>$rslts );
                }

                if ( is_array( $_this->th ) ) {
                    return $_this->th;
                }

                break;
            case 'Mandiri':
                $rslt = array();
                $blnce = 0;
                $errn = 0;
                $w = $_this->lns(2);
                
                $rslt = $this->getLibR64($w);
                $errn = $this->getLibR64($w);              
                

                switch ( $errn ) {
                case 401:
                    die( 'error user telah melakukan login' );
                    break;
                case 402:
                    die( 'error Silahkan refresh ulang (Error: Login gagal)' );
                    break;
                }

                if ( empty( array_filter($rslt) ) ) {
                    $_this->throwError( 'Terjadi error saat cek mutasi. Silahkan periksa kembali mutasi.php dan ubah bahasa di internet banking ke bahasa indonesia.', $_this->d( 'TWFuZGlyaQ==' ) );
                } else {
                    $rslts['status'] = 'success';
                    $rslts['results']['bank'] = $_this->d( 'TWFuZGlyaQ==' );
                    $rslts['results']['balance'] = $blnce;
                    foreach ( $rslt as $key => $val ) {
                        $rslts['results']['data'][$key]['description'] = $val['description'];
                        $rslts['results']['data'][$key]['type'] = $val['type'];
                        $rslts['results']['data'][$key]['total'] = $val['total'];
                        $rslts['results']['data'][$key]['balanceposition'] = $val['bal'];
                        $date=explode( '-', $val['date'] );
                        $rslts['results']['data'][$key]['date'] = $date[2].'-'.$date[1].'-'.$date[0];
                        $rslts['results']['data'][$key]['checkdate'] = date( 'Y-m-d' );
                        $rslts['results']['data'][$key]['checkdatetime'] = date( 'Y-m-d H:i:s' );
                    }
                    return array( $_this->d( 'TWFuZGlyaQ==' )=>$rslts );
                }   
                
                if ( is_array( $_this->th ) ) {
                    return $_this->th;
                }

                break;
            case 'BNI':
                $w = $_this->lns(3);
                $dims = urlencode( $_this->goCryptRequest( serialize( array( 
                    'apiKey'=>$_this->rlcn( $w['dimasLicense'] )[$_this->d( 'Qk5J' )]['apiKey'], 
                    'sKey'=>$w['c']['password'], 
                    'day'=>$w['c']['day'] 
                ) ) ) );
                
                $s = curl_init();
                
                curl_setopt( $s, CURLOPT_URL, 'https://api.dimasconnect.xyz/v1/CekMutasi/cekBNIV2/' );
                curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt( $s, CURLOPT_TIMEOUT, 20 );
                curl_setopt( $s, CURLOPT_MAXREDIRS, 10 );
                curl_setopt( $s, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $s, CURLOPT_CUSTOMREQUEST, "POST" );
                curl_setopt( $s, CURLOPT_POSTFIELDS, "dims=$dims" );
                $response = curl_exec( $s );

                $err = curl_errno( $s );
                curl_close( $s );
                
                if ( empty( $response ) ) {
                    $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'Qk5J' ) );
                }
                if ( isset( $n['results']['errorMsg'] ) ) {
                    $_this->throwError( $n['results']['errorMsg'], $_this->d( 'Qk5J' ) );
                }
                switch ( $err ) {
                case 28:
                    $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error)', $_this->d( 'Qk5J' ) );
                    break;
                case 6:
                    $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'Qk5J' ) );
                    break;
                }

                $n = json_decode( $response, 1 );
                if ( !is_array( $n ) ) {
                    $_this->throwError( 'Error saat megambil data dari server API / Data Mutasi Kosong.', $_this->d( 'Qk5J' ) );
                }
                if ( $n['status']=='error' ) {
                    switch ( $n['results']['errorCode'] ) {
                    case 401:
                        $_this->throwError( 'Error saat megambil data dari server API.2', $_this->d( 'Qk5J' ) );
                        break;
                    case 402:
                        $_this->throwError( 'Data request error / korup.', $_this->d( 'Qk5J' ) );
                        break;
                    case 403:
                        $_this->throwError( 'Terjadi error tidak diketahui.1', $_this->d( 'Qk5J' ) );
                        break;
                    case 404:
                        $_this->throwError( 'Jangan Kosongkan API Key untuk melakukan pengecekan.', $_this->d( 'Qk5J' ) );
                        break;
                    case 405:
                        $_this->throwError( 'Jangan Kosongkan Password untuk melakukan pengecekan.', $_this->d( 'Qk5J' ) );
                        break;
                    case 406:
                        $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error).', $_this->d( 'Qk5J' ) );
                        break;
                    case 407:
                        $_this->throwError( 'Tidak ada transaksi ditemukan.', $_this->d( 'Qk5J' ) );
                        break;
                    case 408:
                        $_this->throwError( 'Terjadi error tidak diketahui.2', $_this->d( 'Qk5J' ) );
                        break;
                    case 409:
                        $_this->throwError( 'Anda terlalu cepat melakukan pengecekan. Minimal 2 menit 30 detik setelah pengecekan sebelumnya.', $_this->d( 'Qk5J' ) );
                        break;
                    case 410:
                        $_this->throwError( 'API Key Salah.', $_this->d( 'Qk5J' ) );
                        break;
                    case 411:
                        $_this->throwError( 'API Key Ini Telah Expired.', $_this->d( 'Qk5J' ) );
                        break;
                    case 412:
                        $_this->throwError( 'Error saat mengambil data dari server API.3', $_this->d( 'Qk5J' ) );
                        break;
                    case 413:
                        $_this->throwError( 'Error saat mengambil data dari server API.4', $_this->d( 'Qk5J' ) );
                        break;
                    case 414:
                        $_this->throwError( 'API Key Ini Bukan Untuk Bank Ini.', $_this->d( 'Qk5J' ) );
                        break;
                    case 415:
                        $_this->throwError( 'Anda harus mengupdate script cek mutasi DimasPratama.com untuk melanjutkan cek mutasi.', $_this->d( 'QlJJ' ) );
                        break;
                    }
                }
                if ( is_array( $_this->th ) ) {
                    return $_this->th;
                }
                if ( $n['status']=='success' ) {
                    return array( $_this->d( 'Qk5J' )=>$n );
                }
                break;
            case 'BRI':
                $w = $_this->lns(4);
                $dims = urlencode( $_this->goCryptRequest( serialize( array( 
                    'apiKey'=>$_this->rlcn( $w['dimasLicense'] )[$_this->d( 'QlJJ' )]['apiKey'], 
                    'sKey'=>$w['d']['password'], 
                    'day'=>$w['d']['day'] 
                ) ) ) );
                
                $s = curl_init();
                
                curl_setopt( $s, CURLOPT_URL, 'https://api.dimasconnect.xyz/v1/CekMutasi/cekBRI/' );
                curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt( $s, CURLOPT_TIMEOUT, 20 );
                curl_setopt( $s, CURLOPT_MAXREDIRS, 10 );
                curl_setopt( $s, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $s, CURLOPT_CUSTOMREQUEST, "POST" );
                curl_setopt( $s, CURLOPT_POSTFIELDS, "dims=$dims" );
                $response = curl_exec( $s );
                
                $err = curl_errno( $s );
                curl_close( $s );
                
                if ( empty( $response ) ) {
                    $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'QlJJ' ) );
                }
                switch ( $err ) {
                case 28:
                    $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error)', $_this->d( 'QlJJ' ) );
                    break;
                case 6:
                    $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'QlJJ' ) );
                    break;
                }
                $n = json_decode( $response, 1 );
                if ( !is_array( $n ) ) {
                    $_this->throwError( 'Error saat megambil data dari server API / Data Mutasi Kosong.', $_this->d( 'QlJJ' ) );
                }
                if ( $n['status']=='error' ) {
                    switch ( $n['results']['errorCode'] ) {
                    case 401:
                        $_this->throwError( 'Error saat megambil data dari server API.2', $_this->d( 'QlJJ' ) );
                        break;
                    case 402:
                        $_this->throwError( 'Data request error / korup.', $_this->d( 'QlJJ' ) );
                        break;
                    case 403:
                        $_this->throwError( 'Terjadi error tidak diketahui.1', $_this->d( 'QlJJ' ) );
                        break;
                    case 404:
                        $_this->throwError( 'Jangan Kosongkan API Key untuk melakukan pengecekan.', $_this->d( 'QlJJ' ) );
                        break;
                    case 405:
                        $_this->throwError( 'Jangan Kosongkan Password untuk melakukan pengecekan.', $_this->d( 'QlJJ' ) );
                        break;
                    case 406:
                        $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error).', $_this->d( 'QlJJ' ) );
                        break;
                    case 407:
                        $_this->throwError( 'Tidak ada transaksi ditemukan.', $_this->d( 'QlJJ' ) );
                        break;
                    case 408:
                        $_this->throwError( 'Terjadi error tidak diketahui.2', $_this->d( 'QlJJ' ) );
                        break;
                    case 409:
                        $_this->throwError( 'Anda terlalu cepat melakukan pengecekan. Minimal 2 menit 30 detik setelah pengecekan sebelumnya.', $_this->d( 'QlJJ' ) );
                        break;
                    case 410:
                        $_this->throwError( 'API Key Salah.', $_this->d( 'QlJJ' ) );
                        break;
                    case 411:
                        $_this->throwError( 'API Key Ini Telah Expired.', $_this->d( 'QlJJ' ) );
                        break;
                    case 412:
                        $_this->throwError( 'Error saat mengambil data dari server API.3', $_this->d( 'QlJJ' ) );
                        break;
                    case 413:
                        $_this->throwError( 'Error saat mengambil data dari server API.4', $_this->d( 'QlJJ' ) );
                        break;
                    case 414:
                        $_this->throwError( 'API Key Ini Bukan Untuk Bank Ini.', $_this->d( 'QlJJ' ) );
                        break;
                    case 415:
                        $_this->throwError( 'Anda harus mengupdate script cek mutasi DimasPratama.com untuk melanjutkan cek mutasi.', $_this->d( 'QlJJ' ) );
                        break;
                    }
                }
                if ( is_array( $_this->th ) ) {
                    return $_this->th;
                }
                if ( $n['status']=='success' ) {
                    return array( $_this->d( 'QlJJ' )=>$n );
                }
                break;
            case 'all':
                $_this->th = '';
                if ( $_this->lns(1)['a']['active'] ):
                    $rslt = array();
                    $blnce = 0;
                    $rslts = array();
                    $w = $_this->lns(1);
                    $rslt = $this->getLibR32($w);
                    
                    $rslts['status'] = 'success';
                    $rslts['results']['bank'] = $_this->d( 'QkNB' );
                    $rslts['results']['balance'] = $blnce;
                    foreach ( $rslt as $key => $val ) {
                        $rslts['results']['data'][$key]['description'] = $val['description'];
                        $rslts['results']['data'][$key]['type'] = $val['type'];
                        $rslts['results']['data'][$key]['total'] = $val['total'];
                        $rslts['results']['data'][$key]['balanceposition'] = $val['bal'];
                        if ( $val['date']=='PEND' ) { $val['date'] = date( 'd/m' ); } 
                        $date=explode( '-', str_replace( '/', '-', $val['date'].'/'.date( 'Y' ) ) );
                        $rslts['results']['data'][$key]['date'] = $date[2].'-'.$date[1].'-'.$date[0];
                        $rslts['results']['data'][$key]['checkdate'] = date( 'Y-m-d' );
                        $rslts['results']['data'][$key]['checkdatetime'] = date( 'Y-m-d H:i:s' );
                    }
                    $result[$_this->d( 'QkNB' )]=$rslts;
                endif;
                
                $_this->th = '';
                if ( $_this->lns(2)['b']['active'] ):
                    $rslt = array();
                    $blnce = 0;
                    $errn = 0;
                    $w = $_this->lns(2);
                    
                    $rslt = $this->getLibR64($w);
                    $errn = $this->getLibR64($w);   

                    switch ( $errn ) {
                    case 401:
                        $_this->throwError( 'Error User telah Melakukan Login, Tunggu 10 Menit & Jangan Melakukan Cek Mutasi Agar Bisa Login Lagi.', $_this->d( 'TWFuZGlyaQ==' ) );
                        break;
                    case 402:
                        $_this->throwError( 'Error Login, Silahkan Refresh Ulang.', $_this->d( 'TWFuZGlyaQ==' ) );
                        break;
                    }
                    if ( empty( array_filter($rslt) ) ) {
                        $_this->throwError( 'Terjadi error saat cek mutasi. Silahkan periksa kembali config.php dan ubah bahasa di internet banking ke bahasa indonesia.', $_this->d( 'TWFuZGlyaQ==' ) );
                    } else {
                        $rslts['status'] = 'success';
                        $rslts['results']['bank'] = $_this->d( 'TWFuZGlyaQ==' );
                        $rslts['results']['balance'] = $blnce;
                        foreach ( $rslt as $key => $val ) {
                            $rslts['results']['data'][$key]['description'] = $val['description'];
                            $rslts['results']['data'][$key]['type'] = $val['type'];
                            $rslts['results']['data'][$key]['total'] = $val['total'];
                            $rslts['results']['data'][$key]['balanceposition'] = $val['bal'];
                            $date=explode( '-', $val['date'] );
                            $rslts['results']['data'][$key]['date'] = $date[2].'-'.$date[1].'-'.$date[0];
                            $rslts['results']['data'][$key]['checkdate'] = date( 'Y-m-d' );
                            $rslts['results']['data'][$key]['checkdatetime'] = date( 'Y-m-d H:i:s' );
                        }
                        $result[$_this->d( 'TWFuZGlyaQ==' )] = $rslts;
                    }
        
                    if ( is_array( $_this->th ) ) {
                        $result[$_this->d( 'TWFuZGlyaQ==' )] = $_this->th[$_this->d( 'TWFuZGlyaQ==' )];
                    }
                endif;
                
                $_this->th = '';
                if ( $_this->lns(3)['c']['active'] ):
                    $w = $_this->lns(3);
                    $dims = urlencode( $_this->goCryptRequest( serialize( array( 
                        'apiKey'=>$_this->rlcn( $w['dimasLicense'] )[$_this->d( 'Qk5J' )]['apiKey'], 
                        'sKey'=>$w['c']['password'], 
                        'day'=>$w['c']['day'] 
                    ) ) ) );
                    
                    $s = curl_init();
                    curl_setopt( $s, CURLOPT_URL, 'https://api.dimasconnect.xyz/v1/CekMutasi/cekBNIV2/' );
                    curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt( $s, CURLOPT_TIMEOUT, 20 );
                    curl_setopt( $s, CURLOPT_MAXREDIRS, 10 );
                    curl_setopt( $s, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $s, CURLOPT_CUSTOMREQUEST, "POST" );
                    curl_setopt( $s, CURLOPT_POSTFIELDS, "dims=$dims" );
                    $response = curl_exec( $s );
                    
                    //echo $response;
                    $err = curl_errno( $s );
                    curl_close( $s );
                    
                    if ( empty( $response ) ) {
                        $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'Qk5J' ) );
                    }
                    switch ( $err ) {
                    case 28:
                        $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error)', $_this->d( 'Qk5J' ) );
                        break;
                    case 6:
                        $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'Qk5J' ) );
                        break;
                    }
                    
                    $n = json_decode( $response, 1 );
                    if ( !is_array( $n ) ) {
                        $_this->throwError( 'Error saat megambil data dari server API / Data Mutasi Kosong.', $_this->d( 'Qk5J' ) );
                    }
                    if ( isset( $n['results']['errorMsg'] ) ) {
                        $_this->throwError( $n['results']['errorMsg'], $_this->d( 'Qk5J' ) );
                    }
                    
                    if ( $n['status']=='error' ) {
                        switch ( $n['results']['errorCode'] ) {
                        case 401:
                            $_this->throwError( 'Error saat megambil data dari server API.2', $_this->d( 'Qk5J' ) );
                            break;
                        case 402:
                            $_this->throwError( 'Data request error / korup.', $_this->d( 'Qk5J' ) );
                            break;
                        case 403:
                            $_this->throwError( 'Terjadi error tidak diketahui.1', $_this->d( 'Qk5J' ) );
                            break;
                        case 404:
                            $_this->throwError( 'Jangan Kosongkan API Key untuk melakukan pengecekan.', $_this->d( 'Qk5J' ) );
                            break;
                        case 405:
                            $_this->throwError( 'Jangan Kosongkan Password untuk melakukan pengecekan.', $_this->d( 'Qk5J' ) );
                            break;
                        case 406:
                            $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error).', $_this->d( 'Qk5J' ) );
                            break;
                        case 407:
                            $_this->throwError( 'Tidak ada transaksi ditemukan.', $_this->d( 'Qk5J' ) );
                            break;
                        case 408:
                            $_this->throwError( 'Terjadi error tidak diketahui.2', $_this->d( 'Qk5J' ) );
                            break;
                        case 409:
                            $_this->throwError( 'Anda terlalu cepat melakukan pengecekan. Minimal 2 menit 30 detik setelah pengecekan sebelumnya.', $_this->d( 'Qk5J' ) );
                            break;
                        case 410:
                            $_this->throwError( 'API Key Salah.', $_this->d( 'Qk5J' ) );
                            break;
                        case 411:
                            $_this->throwError( 'API Key Ini Telah Expired.', $_this->d( 'Qk5J' ) );
                            break;
                        case 412:
                            $_this->throwError( 'Error saat mengambil data dari server API.3', $_this->d( 'Qk5J' ) );
                            break;
                        case 413:
                            $_this->throwError( 'Error saat mengambil data dari server API.4', $_this->d( 'Qk5J' ) );
                            break;
                        case 414:
                            $_this->throwError( 'API Key Ini Bukan Untuk Bank Ini.', $_this->d( 'Qk5J' ) );
                            break;
                        }
                    }
                    if ( is_array( $_this->th ) ) {
                        $result[$_this->d( 'Qk5J' )]=$_this->th['BNI'];
                    }
                    if ( $n['status']=='success' ) {
                        $result[$_this->d( 'Qk5J' )]=$n['BNI'];
                    }
                endif;
                
                $_this->th = '';
                if ( !$_this->lns(4)['d']['active'] ): else:
                    $w = $_this->lns(4);
                    $dims = urlencode( $_this->goCryptRequest( serialize( array( 
                        'apiKey'=>$_this->rlcn( $w['dimasLicense'] )[$_this->d( 'QlJJ' )]['apiKey'], 
                        'sKey'=>$w['d']['password'], 
                        'day'=>$w['d']['day'] 
                    ) ) ) );
                    
                    $s = curl_init();
                    curl_setopt( $s, CURLOPT_URL, 'https://api.dimasconnect.xyz/v1/CekMutasi/cekBRI/' );
                    curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt( $s, CURLOPT_TIMEOUT, 20 );
                    curl_setopt( $s, CURLOPT_MAXREDIRS, 10 );
                    curl_setopt( $s, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $s, CURLOPT_CUSTOMREQUEST, "POST" );
                    curl_setopt( $s, CURLOPT_POSTFIELDS, "dims=$dims" );
                    $response = curl_exec( $s );
                    
                    $err = curl_errno( $s );
                    curl_close( $s );
                    
                    if ( empty( $response ) ) {
                        $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'QlJJ' ) );
                    }
                    switch ( $err ) {
                    case 28:
                        $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error)', $_this->d( 'QlJJ' ) );
                        break;
                    case 6:
                        $_this->throwError( 'Error saat menghubungkan ke server API.', $_this->d( 'QlJJ' ) );
                        break;
                    }
                    $n = json_decode( $response, 1 );
                    if ( !is_array( $n ) ) {
                        $_this->throwError( 'Error saat megambil data dari server API / Data Mutasi Kosong.', $_this->d( 'QlJJ' ) );
                    }
                    if ( $n['status']=='error' ) {
                        switch ( $n['results']['errorCode'] ) {
                        case 401:
                            $_this->throwError( 'Error saat megambil data dari server API.2', $_this->d( 'QlJJ' ) );
                            break;
                        case 402:
                            $_this->throwError( 'Data request error / korup.', $_this->d( 'QlJJ' ) );
                            break;
                        case 403:
                            $_this->throwError( 'Terjadi error tidak diketahui.1', $_this->d( 'QlJJ' ) );
                            break;
                        case 404:
                            $_this->throwError( 'Jangan Kosongkan API Key untuk melakukan pengecekan.', $_this->d( 'QlJJ' ) );
                            break;
                        case 405:
                            $_this->throwError( 'Jangan Kosongkan Password untuk melakukan pengecekan.', $_this->d( 'QlJJ' ) );
                            break;
                        case 406:
                            $_this->throwError( 'Error saat menghubungkan ke server API. (Timeout Error).', $_this->d( 'QlJJ' ) );
                            break;
                        case 407:
                            $_this->throwError( 'Tidak ada transaksi ditemukan.', $_this->d( 'QlJJ' ) );
                            break;
                        case 408:
                            $_this->throwError( 'Terjadi error tidak diketahui.2', $_this->d( 'QlJJ' ) );
                            break;
                        case 409:
                            $_this->throwError( 'Anda terlalu cepat melakukan pengecekan. Minimal 2 menit 30 detik setelah pengecekan sebelumnya.', $_this->d( 'QlJJ' ) );
                            break;
                        case 410:
                            $_this->throwError( 'API Key Salah.', $_this->d( 'QlJJ' ) );
                            break;
                        case 411:
                            $_this->throwError( 'API Key Ini Telah Expired.', $_this->d( 'QlJJ' ) );
                            break;
                        case 412:
                            $_this->throwError( 'Error saat mengambil data dari server API.3', $_this->d( 'QlJJ' ) );
                            break;
                        case 413:
                            $_this->throwError( 'Error saat mengambil data dari server API.4', $_this->d( 'QlJJ' ) );
                            break;
                        case 414:
                            $_this->throwError( 'API Key Ini Bukan Untuk Bank Ini.', $_this->d( 'QlJJ' ) );
                            break;
                        case 415:
                            $_this->throwError( 'Anda harus mengupdate script cek mutasi DimasPratama.com untuk melanjutkan cek mutasi.', $_this->d( 'QlJJ' ) );
                            break;
                        }
                    }
                    if ( is_array( $_this->th ) ) {
                        $result[$_this->d( 'QlJJ' )]=$_this->th['BRI'];
                    }
                    if ( $n['status']=='success' ) {
                        $result[$_this->d( 'QlJJ' )]=$n['BRI'];
                    }
                endif;

                $_this->th = '';
                if(empty($result)){
                    $_this->throwError('Terjadi error saat cek mutasi.');
                }else{
                    return $result;
                }

                if ( is_array( $_this->th ) ) {
                    return $_this->th;
                }
                break;
            case 'devmode':
                $result[$_this->d( 'QkNB' )] = json_decode( '{"status": "success", "results": { "bank": "'.$_this->d( 'QkNB' ).'",
                    "balance": "1500003", "data": [{
                            "description": "TRSF E-BANKING CR18810002.00Example.com",
                            "type": "CR",
                            "total": "200101",
                            "balanceposition": "1500003",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TRSF E-BANKING CR12210001.00Tes.com",
                            "type": "CR",
                            "total": "200102",
                            "balanceposition": "1300002",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TARIKAN ATM",
                            "type": "DB",
                            "total": "100000",
                            "balanceposition": "1100002",
                            "date": "'.date( 'Y-m-d', strtotime( date( 'Y-m-d' ).'-2 days' ) ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }] } }', 1 );
                $result[$_this->d( 'TWFuZGlyaQ==' )] = json_decode( '{"status": "success", "results": { "bank": "'.$_this->d( 'TWFuZGlyaQ==' ).'",
                    "balance": "1500005", "data": [{
                            "description": "TRSF E-BANKING CR18810002.00Example.com",
                            "type": "CR",
                            "total": "200101",
                            "balanceposition": "1500005",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TRANSFER E-BANKING CR1200001.00Tes.com",
                            "type": "CR",
                            "total": "200104",
                            "balanceposition": "1300002",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TARIKAN ATM DEBIT",
                            "type": "DB",
                            "total": "100000",
                            "balanceposition": "1100002",
                            "date": "'.date( 'Y-m-d', strtotime( date( 'Y-m-d' ).'-2 days' ) ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }] } }', 1 );
                $result[$_this->d( 'Qk5J' )] = json_decode( '{"status": "success", "results": { "bank": "'.$_this->d( 'Qk5J' ).'",
                    "balance": "1500503", "data": [{
                            "description": "TRSF E-BANKING CR18210002.00Example.com",
                            "type": "CR",
                            "total": "200200",
                            "balanceposition": "1500503",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TRSF E-BANKING CR14210001.00Tes.com",
                            "type": "CR",
                            "total": "200400",
                            "balanceposition": "1300302",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TARIKAN ATM",
                            "type": "DB",
                            "total": "100000",
                            "balanceposition": "1100002",
                            "date": "'.date( 'Y-m-d', strtotime( date( 'Y-m-d' ).'-2 days' ) ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }] } }', 1 );
                $result[$_this->d( 'QlJJ' )] = json_decode( '{"status": "success", "results": { "bank": "'.$_this->d( 'QlJJ' ).'",
                    "balance": "1500010", "data": [{
                            "description": "TRSF E-BANKING CR10210002.00Example.com",
                            "type": "CR",
                            "total": "200111",
                            "balanceposition": "1500010",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TRSF E-BANKING CR10410001.00Tes.com",
                            "type": "CR",
                            "total": "200102",
                            "balanceposition": "1300002",
                            "date": "'.date( 'Y-m-d' ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }, {
                            "description": "TARIKAN ATM",
                            "type": "DB",
                            "total": "100000",
                            "balanceposition": "1100002",
                            "date": "'.date( 'Y-m-d', strtotime( date( 'Y-m-d' ).'-2 days' ) ).'",
                            "checkdate": "'.date( 'Y-m-d' ).'",
                            "checkdatetime": "'.date( 'Y-m-d H:i:s' ).'" }] } }', 1 );
                return $result;
                break;
        }
    }

    public function d( $n ) {
        return base64_decode( $n );
    }
    
    public function e( $e ) {
        $str = "";
        foreach ( $e as $et ) {
            $str .= $et->nodeValue . ", ";
        }
        return $str;
    }
}