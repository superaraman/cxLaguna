<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\BusinessLogic\blUser;
use Illuminate\Support\Facades\Hash;


class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->oModelUser = new User;
        $this->oBlUser = new blUser($this->oModelUser);
    }

    /**
     * Index page
     *
     * @return view page of Index
     */
    public function index()
    {
        $sTitle = 'Welcome to Laravel!!!';
        return view('pages.index', compact('sTitle'));
    }

    /**
     * About Page
     *
     * @return view page of About
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Services Page
     *
     * @return view page of Services
     */
    public function services()
    {
        $aData = array(
            'sTitle'    =>  'Services',
            'aServices' =>  ['Web Design', 'Programming', 'SEO']
        );
        return view('pages.services')->with($aData);
    }

    /**
     * Setting Pages
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings()
    {
        $this->middleware('auth');
        $aUser = $this->oBlUser->getUserById(auth()->user()->id);
        return view('pages.settings')->with('aUser', $aUser);
    }

    /**
     * Updates the Profile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $this->middleware('auth');
        $aUser = $this->oBlUser->getUserById(auth()->user()->id);

        if ($aUser['user_type'] === null) {
            $this->validate(
                $request, [
                    'username'      =>
                        'required|string|min:4|max:20|unique:users|alpha_dash',
                    'name'          => 'required|string|max:50',
                    'email'         => 'required|string|email|max:255|unique:users',
                    'password'      => 'required|string|min:8|max:16|
                            regex:/^(?=.*[A-z])(?=.*\d).+$/|confirmed',
                    'profile_image' => 'image|nullable|max:4999'
                ]
            );

            if (Hash::check($request['oldpassword'], $aUser['password']) === false) {
                return redirect('/settings')->with('error', 'Incorrect Password');
            }

            $aData = array(
                "username"      => $request['username'],
                "name"          => $request['name'],
                "email"         => $request['email'],
                "oldpassword"   => $request['oldpassword'],
                "password"      => $request['password']
            );

            if ($request->hasFile('profile_image') === true) {
                $aData['profile_image'] = $request['profile_image'];
            }
        } else {
            $this->validate(
                $request, [
                    'username'      =>
                        'required|string|min:4|max:20|unique:users|alpha_dash',
                    'name'          => 'required|string|max:50',
                    'email'         => 'required|string|email|max:255|unique:users',
                    'profile_image' => 'image|nullable|max:4999'
                ]
            );

            $aData = array(
                "username"      => $request['username'],
                "name"          => $request['name'],
                "email"         => $request['email'],
                'password'      => 'passwordGW'
            );

            if ($request->hasFile('profile_image') === true) {
                $aData['profile_image'] = $request['profile_image'];
            }
        }


        $aResult = $this->oBlUser->updateUser($aData, auth()->user()->id);

        return redirect('/settings')->with('success', $aResult['sMsg']);
    }
}
