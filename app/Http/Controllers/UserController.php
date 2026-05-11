<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Validation\Rules\Enum;
class UserController extends Controller
{
    // Display the list of users
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Toggle active/inactive status
    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('status', 'User status updated successfully!');
    }
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => [new Enum(UserRole::class)],
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "تم تحديث رتبة {$user->name} بنجاح!");
    }
    // Delete a user
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete yourself!');
        }

        $user->delete(); // This will now set 'deleted_at' instead of removing the row
        return back()->with('success', 'User moved to trash!');
    }
}