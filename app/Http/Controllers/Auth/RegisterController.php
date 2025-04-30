<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
            function ($attribute, $value, $fail) {
                if (!str_ends_with($value, '@ibitpu.edu.pk')) {
                    $fail('Only @ibitpu.edu.pk email addresses are allowed.');
                }
            },
        ],
        'password' => [
            'required',
            'string',
            'confirmed',
            Password::min(8)
                ->mixedCase()     // At least one uppercase and one lowercase letter
                ->letters()       // Must contain letters
                ->numbers()       // Must contain numbers
                ->symbols(),      // Must contain special characters
        ],
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    Auth::login($user);

    return redirect(route('home'));
}
}
