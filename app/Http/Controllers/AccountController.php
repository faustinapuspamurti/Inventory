<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Lokawisata;

class AccountController extends Controller
{
    public function index_admin(Request $request)
    {
        $query = User::where('role', 'admin'); 

        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        $admins = $query->orderBy('id', 'asc')->get();

        return view('admin.account.admin', compact('admins'));
    }

    public function index_user(Request $request)
    {
        $query = User::with('lokawisatas')->where('role', 'user'); 

        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%')
                ->orWhereHas('lokawisatas', function ($q) use ($request) {
                    $q->where('nama_lokawisata', 'like', '%' . $request->search . '%');
                });
        }
        $lokawisatas = Lokawisata::all();

        $users = $query->orderBy('id', 'asc')->get();

        return view('admin.account.user', compact('users', 'lokawisatas'));
    }

    public function store_admin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('accounts.admin')->with('success', 'Admin berhasil ditambahkan!');
    }

    public function update_admin(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->username = $request->username;

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        return redirect()->route('accounts.admin')->with('success', 'Admin berhasil diperbarui!');
    }

    public function store_user(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'lokawisata_id' => 'exists:lokawisata,id',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);

        $user->lokawisatas()->attach($request->lokawisata_id);

        return redirect()->route('accounts.user')->with('success', 'User berhasil ditambahkan!');
    }

    public function update_user(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'lokawisata_id' => 'exists:lokawisata,id',
        ]);

        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        if ($request->filled('lokawisata_id')) {
            $user->lokawisatas()->sync($request->lokawisata_id);
        }

        return redirect()->route('accounts.user')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $role = $user->role;

        $user->delete();

        $redirectRoute = $role === 'admin'
        ? 'accounts.admin'
        : 'accounts.user';

        return redirect()->route($redirectRoute)->with('success', 'Data pengguna berhasil dihapus.');
    }

}
