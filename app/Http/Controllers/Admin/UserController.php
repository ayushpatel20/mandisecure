<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['buyer', 'seller']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',  'like', "%{$s}%")
                  ->orWhere('email',  'like', "%{$s}%")
                  ->orWhere('mobile', 'like', "%{$s}%")
                  ->orWhere('business_name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role') && in_array($request->role, ['buyer', 'seller'])) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'blocked', 'pending', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'total'    => User::whereIn('role', ['buyer', 'seller'])->count(),
            'buyers'   => User::where('role', 'buyer')->count(),
            'sellers'  => User::where('role', 'seller')->count(),
            'pending'  => User::where('status', 'pending')->count(),
            'blocked'  => User::where('status', 'blocked')->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    public function show(User $user)
    {
        abort_if($user->isAdmin(), 404);
        return view('admin.users.show', compact('user'));
    }

    public function approve(User $user)
    {
        abort_if(!$user->isSeller(), 403);
        abort_if($user->status !== 'pending', 422);
        $user->update(['status' => 'active']);
        return back()->with('success', "Seller account for {$user->name} has been approved.");
    }

    public function reject(User $user)
    {
        abort_if(!$user->isSeller(), 403);
        abort_if($user->status !== 'pending', 422);
        $user->update(['status' => 'rejected']);
        return back()->with('success', "Seller account for {$user->name} has been rejected.");
    }

    public function block(User $user)
    {
        abort_if($user->isAdmin(), 403, 'Cannot block an admin.');
        abort_if($user->isBlocked(), 422);
        $user->update(['status' => 'blocked']);
        return back()->with('success', "User {$user->name} has been blocked.");
    }

    public function unblock(User $user)
    {
        abort_if($user->isAdmin(), 403);
        abort_if($user->status !== 'blocked', 422);
        $user->update(['status' => 'active']);
        return back()->with('success', "User {$user->name} has been unblocked.");
    }
}
