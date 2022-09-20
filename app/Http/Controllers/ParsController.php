<?php

namespace App\Http\Controllers;

use App\Jobs\ParsJob;
use App\Models\Pars;
use Illuminate\Http\Request;

class ParsController extends Controller
{

    public function __invoke()
    {
//        return 1;
//        ParsJob::dispatch()->onQueue('parsing');
        Pars::Pars3();
        return redirect()->back();
    }

}
