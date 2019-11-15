<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use Hash;

class UserController extends Controller
{
    public static $validations = [
        'name' => 'required',
        'email' => 'email',
        'status_id' => 'required',
    ];

    public static $newPasswordValidations = [
        'old_password' => 'required',
        'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,32}/',
    ];

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id)
    {
        $user = User::findOrFail($id);

        if(!empty($id) && is_numeric($id)) {
            $user = User::findOrFail($id);
        }

        $data = [
            'user' => $user,
            'statuses' => User::$statuses,
        ];

        return view('users.form', $data);
    }

    public function passwordForm()
    {
        $user = Auth::user();

        $data = [
            'user' => $user
        ];

        return view('auth.passwords.change', $data);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if(!$request->validate(self::$newPasswordValidations)) {
            return Redirect::back()->withErrors();
        }

        if(Hash::check($request->old_password, $user->password) ) {
            $user->password = Hash::make($request->password);
            
            if($user->hasFlag(User::FLAG_CHANGE_PASSWORD)) {
                $user->flags -= User::FLAG_CHANGE_PASSWORD;
            }

            $user->save();

            return redirect('/')
                    ->with('notification', "Your password has been updated.");
        }

        return redirect('/logout')
                    ->with('error', "Incorrect 'Old' password, logging you out.");
    }

    public function list()
    {
        $users = User::where('id','>','0');

        $data = [
            'statuses' => User::$statuses,
            'users' => $users->paginate(50)
        ];

        return view('users.list', $data);
    }

    public function store($id, Request $request)
    {
        $user = User::findOrFail($id);

        if(!$request->validate(self::$validations)) {
            return Redirect::back()->withErrors();
        }

        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->access_flags = $request->access_flags;
        $user->status_id    = $request->status_id;

        $user->save();

        return redirect('users');
    }

    protected function rules()
    {
        return [
            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,32}/',
        ];
    }
}
