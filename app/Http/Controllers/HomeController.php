<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Language\Drivers\Translation;

class HomeController extends Controller
{
    private $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // If user is authenticated
        if ($request->user()) {
            return redirect()->route('dashboard', [
                'company_uid' => $request->user()->currentCompany()->uid
            ]);
        }

        $theme = get_system_setting('theme');
        return view("themes.$theme.home", [
            'languages' => $this->translation->allLanguages(),
        ]);
    } 

    /**
     * Show the application demo page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function demo(Request $request)
    {
        // If demo mode is not active then deactivate demo page
        if (config('app.is_demo')) {
            return view('layouts.demo');
        };

        return redirect('/');
    } 

    /**
     * Show the api documentation page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function api_docs(Request $request)
    {
        return view('layouts.api_docs');
    } 

    /**
     * Change language and store the locale pref in session
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function change_language(Request $request){
        app()->setlocale($request->locale);
        session()->put('locale', $request->locale); 

        return redirect()->back();
    }
}
