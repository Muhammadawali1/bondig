<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin() 
    {
        if(auth()->check()) {
            return redirect()->route('dashboard'); // atau redirect sesuai role
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        if (Auth::check()) {
            return back();
        }
    
        // Manual validation
        $errors = [];
        
        // Validasi NIP
        if (empty($request->nip)) {
            $errors['nip'] = 'NIP wajib diisi.';
        } elseif (!is_numeric($request->nip)) {
            $errors['nip'] = 'NIP harus berupa angka.';
        } elseif (strlen($request->nip) > 18) {
            $errors['nip'] = 'NIP tidak boleh lebih dari 18 digit.';
        }
        
        // Validasi password
        if (empty($request->password)) {
            $errors['password'] = 'Password wajib diisi.';
        }

        // ✅ VALIDASI CAPTCHA (DITAMBAHKAN)
        if (!env('CAPTCHA_DISABLE', false)) {
            if (empty($request->captcha)) {
                $errors['captcha'] = 'Captcha wajib diisi.';
            } elseif (!captcha_check($request->captcha)) {
                $errors['captcha'] = 'Captcha salah.';
            }
        }
        
        if (!empty($errors)) {
            return redirect('/login')
                        ->withErrors($errors)
                        ->withInput();
        }

        $credentials = [
            'nip' => $request->nip,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect berdasarkan role
            if ($user->role === 'pegawai') {
                return redirect()->route('pegawai.dashboard');
            } elseif ($user->role === 'atasan') {
                return redirect()->route('atasan.dashboard');
            } elseif ($user->role === 'gudang') {
                return redirect()->route('gudang.dashboard');
            }
            
            return redirect('/dashboard');
        }

        // Cek apakah user ada dengan NIP tersebut
        $userExists = \App\Models\User::where('nip', $request->nip)->exists();
        
        throw ValidationException::withMessages([
            'nip' => $userExists ? 
                'Password salah. Silakan coba kembali.' : 
                'NIP tidak terdaftar. Silakan periksa kembali NIP Anda.',
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}