<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
           return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            // PERBAIKAN DISINI: Tambahkan 'unique:users' agar dicek dulu di DB
            'username' => ['required', 'string', 'max:255','unique:users'], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'], // Sebaiknya tambah 'min:8'
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}