<?php

namespace App\Http\Controllers;

use App\Models\Pars;
use Illuminate\Http\Request;

class ParsController extends Controller
{

    public function __invoke()
    {
        Pars::Pars1();
    }

}
