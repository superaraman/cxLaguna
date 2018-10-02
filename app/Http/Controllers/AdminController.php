<?php

namespace App\Http\Controllers;

use App\Http\Models\User;
use Illuminate\Http\Request;
use App\Http\BusinessLogic\blAdmin;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdminController
 *
 * @author  Mark Angelo Mariano <mark04@simplexi.com.ph>
 * @package App\Http\Controllers
 * @since   2018.09.06
 */
class AdminController extends Controller
{
    /**
     * Instance of blAdmin
     *
     * @var blAdmin
     */
    private $oBlAdmin;

    /**
     * Creates a new controller instance. Middleware for limited availability to logged in users
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->oBlAdmin = new blAdmin(new User());
    }

    /**
     * Shows the admin page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (strtoupper(Auth::user()->role) === 'SUPER ADMIN') {
            return view('pages.manage-users');
        } else {
            return redirect('/home');
        }
    }

    /**
     * Returns list of admins and super admins
     *
     * @return \App\User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListofAdmins()
    {
        return $this->oBlAdmin->getListofAdmins();
    }

    /**
     * Get The Current Admin
     * @return mixed
     */
    public function getCurrentAdmin()
    {
        return Auth::user()->username;
    }

    /**
     * Changes the admin role
     *
     * @param Request $request
     * @return array
     */
    public function updateAdminRole(Request $request) : array
    {
        return $this->oBlAdmin->updateAdminRole($request);
    }

    /**
     * Deletes admin based on Groupware ID
     *
     * @param Request $request
     * @return array
     */
    public function deleteAdmin(Request $request) : array
    {
        return $this->oBlAdmin->deleteAdmin($request);
    }
}
