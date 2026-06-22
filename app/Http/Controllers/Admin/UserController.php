<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('admin');

        return view('admin.users.index', ['users' => User::latest()->paginate(12)]);
    }

    public function show(User $user)
    {
        Gate::authorize('admin');

        return view('admin.users.show', [
            'user' => $user->loadCount('landParcels'),
        ]);
    }

    public function edit(User $user)
    {
        Gate::authorize('admin');

        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(UpdateUserRoleRequest $request, User $user)
    {
        Gate::authorize('admin');

        try {
            $user->update($request->validated());

            return redirect()->route('admin.users.show', $user)->with('success', 'User role updated successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function create()
    {
        Gate::authorize('admin');

        return redirect()->route('admin.users.index')->withErrors(['error' => 'Create users through registration.']);
    }

    public function store()
    {
        Gate::authorize('admin');

        return redirect()->route('admin.users.index')->withErrors(['error' => 'Create users through registration.']);
    }

    public function destroy(User $user)
    {
        Gate::authorize('admin');

        try {
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
