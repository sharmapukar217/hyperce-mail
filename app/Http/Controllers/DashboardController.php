<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @return View
     */
    public function __invoke()
    {
        return view('setup.index');
    }
}
