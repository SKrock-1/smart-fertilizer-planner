<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function __invoke(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'hi'], true)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
