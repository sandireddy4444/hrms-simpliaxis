<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return response()->json(['success' => true, 'redirect' => route('dashboard')]);
            }

            return response()->json(['success' => false, 'message' => 'Invalid email or password'], 401);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed: ' . $e->getMessage()], 422);
        } catch (QueryException $e) {
            \Log::error('Database error during login: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database error. Please contact the administrator.'], 500);
        } catch (\Exception $e) {
            \Log::error('Unexpected error during login: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
