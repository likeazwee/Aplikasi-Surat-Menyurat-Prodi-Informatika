<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna dengan fungsionalitas pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('profile')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan formulir untuk mengedit pengguna.
     */
    public function edit(User $user)
    {
        $user->load('profile');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['mahasiswa', 'kaprodi', 'admin'])],
            'nim' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
            ]
        );

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     */
    public function destroy(User $user)
    {
        if ($user->profile) {
            $user->profile->delete();
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}