<?php

namespace App\Http\Controllers\administrator;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Divisi;
use App\Models\PasswordChangeRequest;

class AdministratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user.active');
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalDivisions = Divisi::count();
        $pendingPasswordRequests = PasswordChangeRequest::where('status', 'pending')->count();
        $usersByRole = User::select('role')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        return view('administrator.dashboard', compact(
            'totalUsers',
            'totalDivisions',
            'pendingPasswordRequests',
            'usersByRole'
        ));
    }

    /**
     * Division Management - Index
     */
    public function divisionsIndex()
    {
        $divisions = Divisi::orderBy('nama')->get();
        return view('administrator.divisions.index', compact('divisions'));
    }

    /**
     * Division Management - Create
     */
    public function divisionsCreate()
    {
        return view('administrator.divisions.create');
    }

    /**
     * Division Management - Store
     */
    public function divisionsStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:divisis,nama',
        ]);

        Divisi::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('administrator.divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Division Management - Edit
     */
    public function divisionsEdit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('administrator.divisions.edit', compact('divisi'));
    }

    /**
     * Division Management - Update
     */
    public function divisionsUpdate(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:divisis,nama,' . $id,
        ]);

        $divisi = Divisi::findOrFail($id);
        $divisi->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('administrator.divisions.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Division Management - Destroy
     */
    public function divisionsDestroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        
        // Check if division has users
        if ($divisi->users()->count() > 0) {
            return redirect()->route('administrator.divisions.index')
                ->with('error', 'Tidak dapat menghapus divisi yang masih memiliki pengguna.');
        }

        $divisi->delete();

        return redirect()->route('administrator.divisions.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }

    /**
     * Account Management - Index
     */
    public function accountsIndex()
    {
        $users = User::with('divisiRelation')->orderBy('name')->get();
        $divisions = Divisi::orderBy('nama')->get();
        $roles = User::getRoles();

        return view('administrator.accounts.index', compact('users', 'divisions', 'roles'));
    }

    /**
     * Account Management - Create
     */
    public function accountsCreate()
    {
        $divisions = Divisi::orderBy('nama')->get();
        $roles = User::getRoles();

        return view('administrator.accounts.create', compact('divisions', 'roles'));
    }

    /**
     * Account Management - Store
     */
    public function accountsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:18|unique:users,nip',
            'password' => 'required|string|min:8',
            'role' => 'required|in:atasan,pegawai,gudang,administrator',
            'divisi' => 'nullable|string|exists:divisis,nama',
        ]);

        User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'divisi' => $request->divisi,
        ]);

        return redirect()->route('administrator.accounts.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Account Management - Edit
     */
    public function accountsEdit($id)
    {
        $user = User::with('divisiRelation')->findOrFail($id);
        $divisions = Divisi::orderBy('nama')->get();
        $roles = User::getRoles();

        return view('administrator.accounts.edit', compact('user', 'divisions', 'roles'));
    }

    /**
     * Account Management - Update
     */
    public function accountsUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:18|unique:users,nip,' . $id,
            'role' => 'required|in:atasan,pegawai,gudang,administrator',
            'divisi' => 'nullable|string|exists:divisis,nama',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'role' => $request->role,
            'divisi' => $request->divisi,
        ]);

        return redirect()->route('administrator.accounts.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Account Management - Destroy
     */
    public function accountsDestroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('administrator.accounts.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('administrator.accounts.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Password Approval - Index
     */
    public function passwordRequestsIndex()
    {
        $requests = PasswordChangeRequest::with(['user', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrator.password-requests.index', compact('requests'));
    }

    /**
     * Password Approval - Approve
     */
    public function passwordRequestsApprove($id)
    {
        $request = PasswordChangeRequest::with('user')->findOrFail($id);

        if ($request->status !== 'pending') {
            return redirect()->route('administrator.password-requests.index')
                ->with('error', 'Permintaan ini sudah diproses.');
        }

        // Update user password (new_password is already hashed)
        $request->user->update([
            'password' => $request->new_password,
        ]);

        // Update request status
        $request->update([
            'status' => 'completed',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('administrator.password-requests.index')
            ->with('success', 'Perubahan password berhasil disetujui.');
    }

    /**
     * Password Approval - Reject
     */
    public function passwordRequestsReject(Request $request, $id)
    {
        $passwordRequest = PasswordChangeRequest::findOrFail($id);

        if ($passwordRequest->status !== 'pending') {
            return redirect()->route('administrator.password-requests.index')
                ->with('error', 'Permintaan ini sudah diproses.');
        }

        $passwordRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('administrator.password-requests.index')
            ->with('success', 'Perubahan password ditolak.');
    }
}
