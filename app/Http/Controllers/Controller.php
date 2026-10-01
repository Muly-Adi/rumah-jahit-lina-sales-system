<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function __construct() {
        // membuat variabel yang dapat diakses di semua view
        view()->share('kategori', \App\Models\Kategori::latest()->get());
    }
}
