<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(auth()->check()) {
            $user = auth()->user();
            $type = $user->type;

            if($type === 'employee') {
                return redirect()->route('users.profile');
            }elseif ($type === 'company') {
                return redirect()->route('company.profile');
            }elseif($type === 'admin' || $type === 'super') {
                return redirect()->route('admin.dashboard');
            }else {
                return redirect()->route('logout');
            }
        }

        return view( 'welcome');
    }
}
