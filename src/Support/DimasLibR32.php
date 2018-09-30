<?php

namespace Lugasdev\Mutasi\Support;

trait DimasLibR32 {

    //getting data from bank
    public function getLibR32($w)
    {
        error_reporting( E_ALL );

        $ua = "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36";
        //$client = new \GuzzleHttp\Client();
        $ch = curl_init();
        $r = $w;
    
        curl_setopt( $ch, CURLOPT_COOKIEJAR,  dirname(__FILE__) . '/c0ok1e5s' );    
        curl_setopt( $ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/c0ok1e5s' );
        curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36' );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com' );
        $n = curl_exec( $ch );
        
        $a = strstr($n, 'var s = document.createElement(\'script\'), attrs = { src: (window.location.protocol ==', 1);
        $a = strstr($a, 'function getCurNum(){');$b = array('return "', 'function getCurNum(){', '";', '}', '{', '(function()');
        $b = str_replace($b, '', $a);if(!isset($_SERVER['SERVER_ADDR'])): $_SERVER['SERVER_ADDR'] = '192.168.100.1'; endif;
        $s = trim($b);
        $w = 'value%28actions%29=login&value%28user_id%29=' . $r['a']['username'] . '&value%28CurNum%29=' . $this->a($n) .'&value%28user_ip%29=' . $_SERVER['SERVER_ADDR'] .'&value%28browser_info%29=' . $ua .'&value%28mobile%29=false&value%28pswd%29=' . $r['a']['password'] . '&value%28Submit%29=LOGIN';                                                                                                                                                                                                                                                                                                                                                   /* Script Cek Mutasi https://dimaspratama.com */
        
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com/authentication.do' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ibank.klikbca.com' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $w );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        $n = curl_exec( $ch );
        
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com/nav_bar_indo/menu_bar.htm' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ibank.klikbca.com/authentication.do' );
        $n = curl_exec( $ch );
        
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com/nav_bar_indo/account_information_menu.htm' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ibank.klikbca.com/authentication.do' );
        $n = curl_exec( $ch );
        
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com/balanceinquiry.do' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ibank.klikbca.com/nav_bar_indo/account_information_menu.htm' );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        $n = curl_exec( $ch );
        
        preg_match_all( '/<font face="verdana" size="2" color="#0000bb">(.*?)<\/font>/sim', $n, $dms );
        foreach( $dms[1] as $k => $v ) {
            $v = trim( preg_replace( '/\s*(<br>)\s*/', '<br />', $v ) );
            $dms[0][$k] = $v;
        }
        $dms[0][8] = explode(".",$dms[0][8])[0];
        $dms[0][8] = str_replace(",","",$dms[0][8]);
        
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com/accountstmt.do?value(actions)=acct_stmt' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ibank.klikbca.com/nav_bar_indo/account_information_menu.htm' );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        $n = curl_exec( $ch );
        
        $w = array();
        $t1 = explode( '-', date( 'Y-m-d') );
        $t0 = explode( '-', date('Y-m-d', strtotime('-'.$r['a']["day"].' days')));
        $w[] = 'value%28startDt%29=' . $t0[2];
        $w[] = 'value%28startMt%29=' . $t0[1];
        $w[] = 'value%28startYr%29=' . $t0[0];
        $w[] = 'value%28endDt%29=' . $t1[2];
        $w[] = 'value%28endMt%29=' . $t1[1];
        $w[] = 'value%28endYr%29=' . $t1[0];
        $w[] = 'value%28D1%29=0';
        $w[] = 'value%28r1%29=1';
        $w[] = 'value%28fDt%29=';
        $w[] = 'value%28tDt%29=';
        $w[] = 'value%28submit1%29=Lihat+Mutasi+Rekening';
        $w = implode( '&', $w );
        
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com/accountstmt.do?value(actions)=acctstmtview' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ibank.klikbca.com/nav_bar_indo/account_information_menu.htm' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $w );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        $source = curl_exec( $ch );
        
        curl_setopt( $ch, CURLOPT_URL, 'https://ibank.klikbca.com/authentication.do?value(actions)=logout' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ibank.klikbca.com/nav_bar_indo/account_information_menu.htm' );
        $n = curl_exec( $ch );
        curl_close( $ch );
        
        $source = array_slice( explode( '<b>Saldo</b></font></div></td>', $source ), 1 );
        if ( isset( $source[0] ) )$blnce = $dms[0][8]; 
        {
            $source = array_slice( explode( '</tr>' . "\r\n" . '</table>  </td></tr><tr>', $source[0] ), 0, 1 );
            $source = array_slice( explode( '</tr>' . "\r\n" . '<tr>', $source[0] ), 1 );
            foreach( $source as $key => $val ) { 
                preg_match_all( '/<font face="verdana" size="1" color="#0000bb">(.*?)<\/font>/sim', $val, $matches ); 
                foreach( $matches[1] as $k => $v ) {   
                    $v = trim( preg_replace( '/\s*(<br>)\s*/', '<br />', $v ) );
                    eval(base64_decode('JG5hbWUgPSAiU2NyaXB0IENlayBNdXRhc2kgRGltYXNQcmF0YW1hLmNvbSI7JF9QT1NUWyJjZWtNdXRhc2lOYW1lIl09IlNjcmlwdCBDZWsgTXV0YXNpIERpbWFzUHJhdGFtYS5jb20iOw=='));
                    $matches[0][$k] = $v; 
                }
                
                $source[$key] = implode( '|', $matches[0] );
                $arr = explode("|", $source[$key]);                                                                                                                                                                                                                                                                                                                                              /* Script Cek Mutasi https://dimaspratama.com */
                if($arr[4]=='DB') { 
                    $type = 'DB'; 
                } else { 
                    $type = 'CR'; 
                } 
                
                $total = $arr[3];
                $kredit = $arr['3'];
                $kredit = explode(".",$kredit)[0];
                $kredit = str_replace(",","",$kredit);
                $date = $arr[0];
                $bal = $arr['5'];
                $bal = explode(".",$bal)[0];
                $bal = str_replace(",","",$bal);
                $keterangan = $arr[1];
                
                if($kredit==0) {continue;} 
                
                $arr=array(); 
                $arr['total'] = $kredit; 
                $arr['date'] = $date;
                $arr['description'] = strip_tags($keterangan); 
                $arr['type'] = $type; 
                $arr['bal'] = $bal;
                $rslt[]=$arr;
            }

            return $rslt;
        }
    }
}