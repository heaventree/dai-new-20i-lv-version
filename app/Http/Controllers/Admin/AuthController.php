<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) return redirect()->route('admin.dashboard');
        return view('admin.login');
    }
    public function login(Request $request)
    {
        $request->validate(['username' => 'required', 'password' => 'required']);
        $user = AdminUser::where('username', $request->username)->first();
        \Illuminate\Support\Facades\Log::debug('DAI LOGIN ATTEMPT', [
            'username_received' => $request->username,
            'password_length'   => strlen($request->password),
            'user_found'        => $user ? 'YES' : 'NO',
            'hash_check'        => $user ? (Hash::check($request->password, $user->password_hash) ? 'PASS' : 'FAIL') : 'N/A',
        ]);
        if ($user && Hash::check($request->password, $user->password_hash)) {
            session(['admin_logged_in' => true, 'admin_username' => $user->username]);
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['login' => 'Invalid username or password.'])->withInput();
    }
    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_username']);
        return redirect()->route('admin.login');
    }
}
