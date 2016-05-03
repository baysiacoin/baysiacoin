<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller {
    /**
     * Set the application locale to $code.
     *
     * @return Response
     */
    public function setLanguage($code)
    {
        Session::set('locale', $code);
        return redirect()->back();
    }
}
