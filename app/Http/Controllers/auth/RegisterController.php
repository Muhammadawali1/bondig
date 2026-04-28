<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        $divisions = \App\Models\Divisi::orderBy('nama')->get();
        return view('auth.register', compact('divisions'));
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        // Manual validation
        $errors = [];
        
        // Validasi name
        if (empty($request->name)) {
            $errors['name'] = 'Nama wajib diisi.';
        }
        
        // Validasi NIP
        if (empty($request->nip)) {
            $errors['nip'] = 'NIP wajib diisi.';
        } elseif (!is_numeric($request->nip)) {
            $errors['nip'] = 'NIP harus berupa angka.';
        } elseif (strlen($request->nip) > 18) {
            $errors['nip'] = 'NIP tidak boleh lebih dari 18 digit.';
        } else {
            // Cek unique
            $exists = \App\Models\User::where('nip', $request->nip)->exists();
            if ($exists) {
                $errors['nip'] = 'NIP sudah terdaftar. Silakan gunakan NIP yang lain.';
            }
        }
        
        // Validasi password
        if (empty($request->password)) {
            $errors['password'] = 'Password wajib diisi.';
        } elseif (strlen($request->password) < 8) {
            $errors['password'] = 'Password minimal 8 karakter.';
        } elseif ($request->password !== $request->password_confirmation) {
            $errors['password'] = 'Konfirmasi password tidak cocok.';
        }
        
        // Validasi divisi
        if (empty($request->divisi)) {
            $errors['divisi'] = 'Divisi wajib dipilih.';
        } else {
            $divisiExists = \App\Models\Divisi::where('nama', $request->divisi)->exists();
            if (!$divisiExists) {
                $errors['divisi'] = 'Divisi tidak valid.';
            }
        }

        // ✅ VALIDASI CAPTCHA (DITAMBAHKAN)
        if (empty($request->captcha)) {
            $errors['captcha'] = 'Captcha wajib diisi.';
        } elseif (!\App\Helpers\CaptchaHelper::check($request->captcha)) {
            $errors['captcha'] = 'Captcha salah. Harus sesuai huruf besar/kecil.';
        }
        
        if (!empty($errors)) {
            return redirect('/register')
                        ->withErrors($errors)
                        ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'role' => 'pegawai',
            'divisi' => $request->divisi,
        ]);

        // Auto login after registration
        auth()->login($user);

        // Redirect ke dashboard pegawai
        return redirect()->route('pegawai.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Bonn DIG.');
    }
}