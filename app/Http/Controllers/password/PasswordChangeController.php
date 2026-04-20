<?php

namespace App\Http\Controllers\password;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PasswordChangeRequest;
use App\Models\Notifikasi;

class PasswordChangeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // User biasa melihat permintaan mereka sendiri
        $requests = PasswordChangeRequest::with(['user', 'approvedBy'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        
        return view('password.index', compact('requests'));
    }

    public function createRequest()
    {
        return view('password.request');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        // Cek apakah sudah ada permintaan pending
        $existingRequest = PasswordChangeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
            
        if ($existingRequest) {
            return back()->with('error', 'Anda sudah memiliki permintaan ubah password yang sedang diproses.');
        }

        // Buat permintaan baru
        $passwordRequest = PasswordChangeRequest::create([
            'user_id' => $user->id,
            'new_password' => Hash::make($request->new_password),
        ]);

        // Buat notifikasi untuk administrator
        $this->createNotificationForAdmins($passwordRequest);

        return redirect()->route('password.index')
            ->with('success', 'Permintaan ubah password telah dikirim ke administrator.');
    }

    public function approve($id)
    {
        // Hanya administrator yang bisa approve
        if (!Auth::user()->isAdministrator()) {
            abort(403);
        }

        $passwordRequest = PasswordChangeRequest::findOrFail($id);
        
        if ($passwordRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        // Update password user langsung saat disetujui
        $user = $passwordRequest->user;
        $user->password = $passwordRequest->new_password;
        $user->save();

        // Update status permintaan menjadi completed
        $passwordRequest->update([
            'status' => 'completed',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Buat notifikasi untuk user
        Notifikasi::create([
            'user_id' => $passwordRequest->user_id,
            'password_change_request_id' => $passwordRequest->id,
            'judul' => 'Password Berhasil Diubah',
            'pesan' => 'Permintaan ubah password Anda telah disetujui oleh administrator. Password Anda telah berhasil diubah.',
            'tipe' => Notifikasi::TIPE_PASSWORD_APPROVED,
            'dibaca' => false,
        ]);

        return back()->with('success', 'Permintaan ubah password telah disetujui dan password berhasil diubah.');
    }

    public function reject(Request $request, $id)
    {
        // Hanya administrator yang bisa reject
        if (!Auth::user()->isAdministrator()) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $passwordRequest = PasswordChangeRequest::findOrFail($id);
        
        if ($passwordRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        // Update status permintaan
        $passwordRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Buat notifikasi untuk user
        Notifikasi::create([
            'user_id' => $passwordRequest->user_id,
            'password_change_request_id' => $passwordRequest->id,
            'judul' => 'Permintaan Ubah Password Ditolak',
            'pesan' => 'Permintaan ubah password Anda ditolak. Alasan: ' . $request->rejection_reason,
            'tipe' => Notifikasi::TIPE_PASSWORD_REJECTED,
            'dibaca' => false,
        ]);

        return back()->with('success', 'Permintaan ubah password telah ditolak.');
    }

    public function directChange()
    {
        // Hanya untuk administrator
        if (!Auth::user()->isAdministrator()) {
            abort(403);
        }
        
        return view('password.admin.change');
    }

    public function storeDirectChange(Request $request)
    {
        // Hanya untuk administrator
        if (!Auth::user()->isAdministrator()) {
            abort(403);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah.');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('password.index')
            ->with('success', 'Password berhasil diubah.');
    }

    private function createNotificationForAdmins($passwordRequest)
    {
        $admins = User::where('role', User::ROLE_ADMINISTRATOR)->get();
        
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'password_change_request_id' => $passwordRequest->id,
                'judul' => 'Permintaan Ubah Password Baru',
                'pesan' => $passwordRequest->user->name . ' mengajukan permintaan ubah password.',
                'tipe' => Notifikasi::TIPE_PASSWORD_REQUEST,
                'dibaca' => false,
                'url' => '/administrator/password-requests',
            ]);
        }
    }
}
