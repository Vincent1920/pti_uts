<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Menangani proses registrasi.
     */
   public function register(Request $request)
    {
       $this->validator($request->all())->validate();
        $user = $this->create($request->all());

        try {
            $user->sendEmailVerificationNotification();
            $message = 'Registrasi berhasil! Silakan cek inbox email Anda untuk verifikasi.';
            $type = 'success';
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email verifikasi: ' . $e->getMessage());

            $message = 'Registrasi berhasil, namun GAGAL mengirim email verifikasi. Silakan hubungi admin atau coba login dan minta kirim ulang nanti.';
            $type = 'warning'; // Ganti warna jadi kuning/merah di alert login nanti
        }

        // 4. Redirect ke Login
        return redirect()->route('login')->with($type, $message);
    }

    /**
     * Validasi data yang masuk.
     */
   protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:users'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        
        // HAPUS 'confirmed' di sini. Cukup 'required' dan 'min:8'
        'password' => ['required', ], 
    ]);
}

    /**
     * Logika pembuatan user di database.
     */
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