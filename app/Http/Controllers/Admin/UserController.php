<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'last_education' => 'nullable|string',
            'is_admin' => 'boolean'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'last_education' => $request->last_education,
            'is_admin' => $request->has('is_admin')
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Verify user email manually
     */
    public function verifyEmail(User $user)
    {
        if ($user->email_verified_at) {
            return redirect()->back()
                ->with('info', 'Email user sudah terverifikasi');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->back()
            ->with('success', 'Email user berhasil diverifikasi');
    }

    /**
     * Toggle admin status
     */
    public function toggleAdmin(User $user)
    {
        // Prevent removing admin from yourself
        if ($user->id === auth()->id() && $user->is_admin) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus status admin dari akun sendiri');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $status = $user->is_admin ? 'dijadikan admin' : 'dihapus status adminnya';

        return redirect()->back()
            ->with('success', "User berhasil {$status}");
    }
}
