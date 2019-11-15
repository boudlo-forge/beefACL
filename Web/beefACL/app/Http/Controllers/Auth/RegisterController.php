<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $user = new User();

        $data = [
            'user' => $user,
            'riskFlags' => User::$riskFlags,
        ];

        return view('auth.register', $data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable','string', 'min:8', 'confirmed'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //dd($data);
        $user = new User();
        $fallbackEmail = bin2hex(random_bytes(8)) . '@members.sheds.gg';
        $email = !array_key_exists('email', $data) || empty($data['email']) ? $fallbackEmail : $data['email'];

        $password = !array_key_exists('password', $data) || empty($data['password']) ? bin2hex(random_bytes(8)) : $data['email'];

        $user->name       = $data['name'];
        $user->legal_name = $data['legal_name'];
        $user->email      = $email;
        $user->contact_me = array_key_exists('contact_me', $data) && $data['contact_me'] ? 1 : 0;
        $user->address_1  = $data['address_1'];
        $user->address_2  = $data['address_2'];
        $user->address_3  = $data['address_3'];
        $user->post_code   = $data['post_code'];
        $user->phone      = $data['phone'];
        $user->phone_2    = $data['phone_2'];

        $user->emergency_contact_name_1  = $data['emergency_contact_name_1'];
        $user->emergency_contact_phone_1 = $data['emergency_contact_phone_1'];
        $user->emergency_contact_name_2  = $data['emergency_contact_name_2'];
        $user->emergency_contact_phone_2 = $data['emergency_contact_phone_2'];

        $user->risk_flags = 0;

        foreach(User::$riskFlags as $key => $flag) {
            if(array_key_exists('risk_flag_' . $key, $data) && !is_null($data['risk_flag_' . $key])) {
                if(!($user->hasRiskFlag($key))) {
                    $user->risk_flags += $key;
                }
            }
        }

        $user->risk_notes = $data['risk_notes'];
        $user->password = Hash::make($data['password']);
        
        $user->save();

        return $user;
    }
}
