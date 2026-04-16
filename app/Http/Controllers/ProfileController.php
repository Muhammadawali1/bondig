<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile user (dummy/basic)
     */
    public function index()
    {
        $user = Auth::user(); // ambil data user login
        return view('profile.index', compact('user'));
    }

    /**
     * Tampilkan halaman edit profile
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/profile'), $photoName);
            $user->photo = $photoName;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile berhasil diperbarui');
    }
}