<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        // Recherche
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($roleId = $request->get('role')) {
            $query->whereHas('roles', function($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        // Filtre par statut
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $user = null;
        return view('user.create', compact(['roles', 'user']));
    }

    /**
     * Store a newly created user.
     */
    public function store(UserRequest $request)
    {
        // dd();
        $data = $request->validated();

        // Gestion du mot de passe
        $data['password'] = Hash::make($data['password']);

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // $data['photo'] = $request->file('photo')->store('users', 'public');
            $photo = $request->file('photo');
            $filename = 'student_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/images/users', $filename);
            $data['photo'] = Storage::url($path);
        }

        $user = User::create($data);

        if ($request->has('roles')) {
            $user->roles()->attach($request->input('roles'));
        }

        // Attribution des rôles
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        // Gestion du mot de passe
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // Suppression de l'ancienne photo
            if ($user->photo) {

                Storage::delete(str_replace('/storage', 'public', $user->photo));
                // Storage::disk('public')->delete($user->photo);
            }
            // $data['photo'] = $request->file('photo')->store('users', 'public');
            $photo = $request->file('photo');
            $filename = 'users_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/images/users', $filename);
            $data['photo'] = Storage::url($path);
        }

        $user->update($data);

        // Mise à jour des rôles
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Suppression de la photo si elle existe
        if ($user->photo) {

            Storage::delete(str_replace('/storage', 'public', $user->photo));
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
