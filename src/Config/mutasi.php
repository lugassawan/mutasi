<?php

return [
    //Dimas License Anda
    'license' =>  env('DIMAS_LICENSE', ''),

    /*
        Ubah ke true tanpa tanda kutip jika ingin mengaktifkan atau false jika ingin menonatifkan
		cekTerkontrol jika diaktifkan akan berfungsi sebagai pengatur waktu pengecekan transaksi agar stabil & lebih aman
		cekTerkontrol jika diaktifkan akan hanya mengecek cekmutasi minimal 10 menit sekali walaupun cron job di set dibawah 10 menit sekali(misalnya 1 menit sekali)
    */
    'control' => env('CHECK_CONTROL', false),

    /*
        Ubah ke true tanpa tanda kutip jika ingin menampilkan output hasil cek mutasi dalam bentuk HTML.
		Ubah ke false tanpa tanda kutip jika tidak ingin menampilkan output hasil cek mutasi dalam bentuk HTML.
    */
    'html_output' => env('HTML_OUTPUT', true),

    /*
        Internet Banking
        Ubah 'active' ke `true` jika ingin mengaktifkan cek mutasi bank atau `false` jika ingin mematikan.
        'day' untuk mengambil mutasi berapa hari kebelakang.
    */

    'banks' => [

        'bca' => [
            'active' => env('BCA_ACTIVE', false),
            'username' => env('BCA_USERNAME', ''),
            'password' => env('BCA_PASSWORD', ''),
            'day' => env('BCA_DAY', 1),
        ],

        'mandiri' => [
            'active' => env('MANDIRI_ACTIVE', false),
            'username' => env('MANDIRI_USERNAME', ''),
            'password' => env('MANDIRI_PASSWORD', ''),
            'rekening' => env('MANDIRI_REKENING', ''),
            'day' => env('MANDIRI_DAY', 1),
        ],

        'bni' => [
            'active' => env('BNI_ACTIVE', false),
            'password' => env('BNI_PASSWORD', ''),
            'day' => env('BNI_DAY', 1),
        ],

        'bri' => [
            'active' => env('BRI_ACTIVE', false),
            'password' => env('BRI_PASSWORD', ''),
            'day' => env('BRI_DAY', 1),
        ],
    ],

    /*
        DIMAS_LICENSE=
        CHECK_CONTROL=
        HTML_OUTPUT=
        
        BCA_ACTIVE=
        BCA_USERNAME=
        BCA_PASSWORD=
        BCA_DAY=
        
        MANDIRI_ACTIVE=
        MANDIRI_USERNAME=
        MANDIRI_PASSWORD=
        MANDIRI_REKENING=
        MANDIRI_DAY=
        
        BNI_ACTIVE=
        BNI_PASSWORD=
        BNI_DAY=
        
        BRI_ACTIVE=
        BRI_PASSWORD=
        BRI_DAY=
    */
];