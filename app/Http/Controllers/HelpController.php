<?php

namespace App\Http\Controllers;

use App\Models\Help;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function welcome_screen_on(){
        $oUser = User::find(Auth::user()->id);
        $oUser->show_welcome_screen = 1;
        $oUser->save();
        return redirect(route('dashboard'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('help.index');
    }

    public function about()
    {
        return view('help.about');
    }

    public function acls()
    {
        return view('help.access-control');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Help $help)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Help $help)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Help $help)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Help $help)
    {
        //
    }
}
