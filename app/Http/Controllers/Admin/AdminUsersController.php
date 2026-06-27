<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    public function index()
    {
        $users = AdminUser::orderBy('id')->get();
        return view('admin.admin-users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:admin_users,username',
            'email'    => 'nullable|email|max:200',
            'password' => 'required|string|min:8|confirmed',
        ]);

        AdminUser::create([
            'username'      => $request->username,
            'email'         => $request->email ?: null,
            'password_hash' => Hash::make($request->password),
            'role'          => 'admin',
        ]);

        return back()->with('success', 'Admin user "' . $request->username . '" created.');
    }

    public function update(Request $request, AdminUser $adminUser)
    {
        $rules = [
            'username' => 'required|string|max:100|unique:admin_users,username,' . $adminUser->id,
            'email'    => 'nullable|email|max:200',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }
        $request->validate($rules);

        $adminUser->username = $request->username;
        $adminUser->email    = $request->email ?: null;
        if ($request->filled('password')) {
            $adminUser->password_hash = Hash::make($request->password);
        }
        $adminUser->save();

        return back()->with('success', 'User "' . $adminUser->username . '" updated.');
    }

    public function destroy(Request $request, AdminUser $adminUser)
    {
        if (session('admin_id') == $adminUser->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $adminUser->delete();
        return back()->with('success', 'Admin user deleted.');
    }
}
