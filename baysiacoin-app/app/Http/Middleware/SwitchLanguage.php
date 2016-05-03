<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Session\Store;
use GeoIP;
class SwitchLanguage {
    protected $session;

    public function __construct(Store $session){
        $this->session=$session;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if there is no session data of language
        if (!$this->session->has('locale')) {
            // when register if the url contains country code such like '/register/india', set the language to english, and are the same to the others.             
            if (strpos($_SERVER['REQUEST_URI'], 'register/')) 
            {
                foreach (Config::get('conf.language.en') as $country) {
                    if (strstr(strtolower($_SERVER['REQUEST_URI']), strtolower($country)) == strtolower($country)) {
                        $this->session->put('locale', 'en');
                    }
                }
                if (strstr(strtolower($_SERVER['REQUEST_URI']), strtolower(Config::get('conf.language.cn'))) == strtolower(Config::get('conf.language.cn'))) {
                    $this->session->put('locale', 'cn');
                }
                if (strstr(strtolower($_SERVER['REQUEST_URI']), strtolower(Config::get('conf.language.ja'))) == strtolower(Config::get('conf.language.ja'))) {
                    $this->session->put('locale', 'ja');
                }
            }             
            else // if not, set language matching the ip region
            { 
                $location = GeoIP::getLocation();                
                if (in_array($location['country'], Config::get('conf.language.en'))) {
                    $this->session->put('locale', 'en');
                } else if ($location['country'] == 'China') {
                    $this->session->put('locale', 'cn');
                }
            }
        }
        // set the locale as given the session parameter
        App::setLocale($this->session->get('locale', 'ja'));
        
        return $next($request);
    }
}