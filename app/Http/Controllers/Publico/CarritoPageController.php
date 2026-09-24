<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CarritoPageController extends Controller
{
    public function index(): View
    {
        return view('publico.carrito', [
            'nombreCliente' => Auth::guard('cliente')->user()?->nombre,
        ]);
    }
}
