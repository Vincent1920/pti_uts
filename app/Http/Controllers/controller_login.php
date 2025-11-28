<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class controller_login extends Controller
{
    public function view_login(){
        return view('pages.login.login');
    }

    public function index()
    {
        return view('home');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome(): View

    {

        return view('adminHome');

    }

   public function login(Request $request)
{
    // 1. Validasi Input (CAPTCHA sudah dihapus)
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 2. Cek apakah email ada di database terlebih dahulu
    $user = User::where('email', $request->email)->first();

    // Jika user tidak ditemukan (Email belum ada)
    if (!$user) {
        return redirect('login')->with('error', 'Email belum terdaftar. Silakan registrasi.');
    }

    // 3. Coba Login (Cek Password)
    // Auth::attempt akan mengecek apakah password cocok dengan email
    if (Auth::attempt($request->only('email', 'password'))) {
        
        // Regenerate session untuk keamanan (mencegah session fixation)
        $request->session()->regenerate();

        // Cek Role
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin');
        } else {
            return redirect('/')->with('success', 'Login Berhasil!');
        }
    } else {
        // 4. Jika email ada tapi Auth::attempt gagal, berarti Password Salah
        return redirect('login')
            ->with('error', 'Password yang Anda masukkan salah.');
    }
}
            public function logout(Request $request)
            {
                // Debugging Auth user sebelum logout
                // dd(Auth::user());
            
                // Auth::logout();
            
                // Menghapus semua sesi
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            
                return redirect('/login')->with('status', 'You have been logged out.');
            }

            public function forgot_password($token){
                return view('pages.login.forgot_password'
                ,['token' => $token]);
            }
}
