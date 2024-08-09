<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
        $data = Auth::user();
        return view('profil.index', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        $employee = Employee::where('user_id', $user->id)->first();
        $employee->name = $request['name'];

        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/images/users', $imageName);

            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete('images/users/'.$user->profile_picture);
            }

            // Update user with new profile picture path
            $user->profile_picture = $imageName;
        }

        $employee->save();
        $user->save();

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
