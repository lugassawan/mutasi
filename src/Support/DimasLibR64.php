<?php

namespace Lugasdev\Mutasi\Support;

trait DimasLibR64 {
    
    //getting data from bank
    public function getLibR64($w)
    {
        error_reporting( E_ALL );
        
        $ch = curl_init();
        $r = $w;
        
        curl_setopt( $ch, CURLOPT_COOKIEJAR,  dirname( __FILE__ ) . '/c0ok1e5s' );
        curl_setopt( $ch, CURLOPT_COOKIEFILE, dirname( __FILE__ ) . '/c0ok1e5s' );
        curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36' );                                                                                                                                                                                                                                                                                                                                                   /* Script Cek Mutasi Dimas Pratama dimaspratama.com */
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/Login.do?action=form&lang=in_ID' );
        $s = curl_exec( $ch );

        $w = 'action=result&userID='.$r['b']['username'].'&password='.$r['b']['password'].'&image.x=0&image.y=0';
        
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/Login.do' );
        curl_setopt( $ch, CURLOPT_REFERER, 'https://ib.bankmandiri.co.id/retail/Login.do?action=form&lang=in_ID' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $w );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        $s = curl_exec( $ch );
        
        if ( strpos( $s, 'User Telah Melakukan Login' ) !== false ) {
            $err = 401;
            return $err;
        } elseif ( strpos( $s, 'Pengguna Baru / Registrasi Ulang' ) !== false ) {
            $err = 402;
            return $err;
        } else {
            curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/Redirect.do?action=forward' );
            curl_setopt( $ch, CURLOPT_REFERER, 'https://ib.bankmandiri.co.id/retail/Login.do?action=form&lang=in_ID' );
            $s = curl_exec( $ch );

            curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/common/menu.jsp' );
            curl_setopt( $ch, CURLOPT_REFERER, '' );
            $s = curl_exec( $ch );
            
            curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/TrxHistoryInq.do?action=form' );
            curl_setopt( $ch, CURLOPT_REFERER, '' );
            $s = curl_exec( $ch );
            
            $a = strstr( $s, '">'.$r['b']['rekening'].' - Tabungan Rp.', 1 );
            $a = strstr( $a, 'fromAccountID"><option' );
            $b = str_replace( 'fromAccountID"><option value="">Silahkan Pilih</option>', '', $a );
            $b = str_replace( '<option value="', '', $b );
            $a0 = trim( $b );
            
            $params = array();
            $t1 = explode( '-', date( 'Y-m-d' ) );
            $t0 = explode( '-', date( 'Y-m-d', strtotime( '-'.$r['b']['day'].' days' ) ) );
            $params[] = 'action=result';
            $params[] = 'fromAccountID=' . $a0;
            $params[] = 'searchType=R';
            $params[] = 'fromDay=' .$t0[2];
            $params[] = 'fromMonth=' . $t0[1];
            $params[] = 'fromYear='. $t0[0];
            $params[] = 'toDay=' . $t1[2];
            $params[] = 'toMonth=' . $t1[1];
            $params[] = 'toYear=' . $t1[0];
            $params[] = 'sortType=Date';
            $params[] = 'orderBy=ASC';
            $params = implode( '&', $params );

            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/TrxHistoryInq.do' );
            curl_setopt( $ch, CURLOPT_REFERER, 'https://ib.bankmandiri.co.id/retail/TrxHistoryInq.do?action=form' );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
            eval(base64_decode('JG5hbWUgPSAiU2NyaXB0IENlayBNdXRhc2kgRGltYXNQcmF0YW1hLmNvbSI7JF9QT1NUWyJjZWtNdXRhc2lOYW1lIl09IlNjcmlwdCBDZWsgTXV0YXNpIERpbWFzUHJhdGFtYS5jb20iOw=='));
            curl_setopt( $ch, CURLOPT_POST, 1 );
            $source = curl_exec( $ch );

            curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/AccountDetail.do?action=result&ACCOUNTID='. $a0 );
            curl_setopt( $ch, CURLOPT_REFERER, 'https://ib.bankmandiri.co.id/retail/AccountList.do?action=acclist' );
            $s = curl_exec( $ch );

            preg_match_all( '/<td height="25" width="304">(.*?)<\/td>/sim', $s, $matches );
            $blnce = @strip_tags(trim($matches[0][4]));
            
            if($blnce): $blnce = @explode(',', $blnce)[0];$blnce = str_replace('Rp.', '', $blnce);$blnce = str_replace('&nbsp;', '', $blnce);$blnce = trim(str_replace('.', '', $blnce)); else: $blnce = false; endif;/* Script Cek Mutasi Dimas Pratama dimaspratama.com */
            
            $blnce = preg_replace('/(\v|\s)+/', '', $blnce);
            
            curl_setopt( $ch, CURLOPT_URL, 'https://ib.bankmandiri.co.id/retail/Logout.do?action=result' );
            curl_setopt( $ch, CURLOPT_REFERER, 'https://ib.bankmandiri.co.id/retail/common/banner.jsp' );
            $s = curl_exec( $ch );
            
            $a = strstr( $source, "<!-- End of Item List -->", true );
            $b = strstr( $a, '<!-- Start of Item List -->' );
            $filtered_words = array('<!-- Start of Item List -->','<!-- End of Item List -->');
            $a0 = str_replace( $filtered_words, '', $b );
            $contents = $a0;
            $DOM = new DOMDocument;
            $DOM->loadHTML( $contents );
            $items = $DOM->getElementsByTagName( 'tr' );
            $n = 0;
            foreach ( $items as $node ) {
                $n++;
                $as = e( $node->childNodes ) . "\n";
                $arr = explode( "\n", $as );
                $date = str_replace( ',', '', $arr[0] );
                $keterangan = str_replace( ',', '', $arr[1] );
                $debit = @str_replace( ',', '', $arr[2] );
                $kredit = @str_replace( ',', '', $arr[3] );
                $kredit = explode( ",", $kredit )[0];
                $kredit = trim(str_replace( ".", "", $kredit ));
                $kredit = substr( $kredit, 0, -2 );
                
                if($kredit==0) {
                    $type = 'DB';
                    $kredit = $debit;
                    $kredit = explode( ",", $kredit )[0];
                    $kredit = trim(str_replace( ".", "", $kredit ));
                    $kredit = substr( $kredit, 0, -2 ); 
                } else {  
                    $type = 'CR';
                } 
                $date = trim(str_replace( "/", "-", $date ));
                
                if ( $kredit==0 ) {continue;}
                
                $arr=array(); 
                $arr['total'] = $kredit; 
                $arr['date'] = $date; 
                $arr['description'] = strip_tags(trim($keterangan)); 
                $arr['type'] = $type; 
                $arr['bal'] = false;
                $rslt[]=$arr;
            }

            return $rslt;
        }
    }
}