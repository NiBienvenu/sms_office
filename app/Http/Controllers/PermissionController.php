<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $permissions = Permission::all();

        return view('permission.index', compact('permissions'));
    }

    public function create(Request $request): View
    {
        return view('permission.create');
    }

    public function store(PermissionStoreRequest $request): RedirectResponse
    {
        $permission = Permission::create($request->validated());

        $request->session()->flash('permission.id', $permission->id);

        return redirect()->route('permissions.index');
    }

    public function show(Request $request, Permission $permission): View
    {
        return view('permission.show', compact('permission'));
    }

    public function edit(Request $request, Permission $permission): View
    {
        return view('permission.edit', compact('permission'));
    }

    public function update(PermissionUpdateRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update($request->validated());

        $request->session()->flash('permission.id', $permission->id);

        return redirect()->route('permissions.index');
    }

    public function destroy(Request $request, Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('permissions.index');
    }
}
