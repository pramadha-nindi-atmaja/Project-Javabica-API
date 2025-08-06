<?php

return [

    /*
    |--------------------------------------------------------------------------
    | image upload Name
    |--------------------------------------------------------------------------
    |
    | DIGUNAKAN UNTUK VALIDASI UPLOAD GAMBAR
    |
    */
    'image_upload' => 'mimes:jpg,png,jpeg,svg|max:2000',
    /*
    |--------------------------------------------------------------------------
    | COUNTRY PHONE LIST VALIDATION
    |--------------------------------------------------------------------------
    |
    | config ini bertujuan untuk validasi terhadap login dan juga register ketika
    | user melakukan registrasi menggunakan HP, dan list ini berfungsi untuk 
    | whitelist lokasi nomer hp mana saja yang di perbolehkan untuk 
    | melakukan pendaftaran
    | apabila lebih daripada 1 maka ditulisnya adalah
    | ID,EB,US
    | kode tersebut bisa di dapatkan di
    | https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements
    */

    'PHONE_COUNTRY_VALIDATION' => env('PHONE_COUNTRY_VALIDATION', 'ID'),

     /*
    |--------------------------------------------------------------------------
    | PASSWORD VALIDATION
    |--------------------------------------------------------------------------
    |
    | RUMUS KOMBINASI PASSWORD
    |
    */


];
