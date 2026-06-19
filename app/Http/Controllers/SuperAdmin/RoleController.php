<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Roles;
use App\Services\SuperAdmin\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService) {}

    public function index(): View
    {
        return view('super-admin.roles.index', [
            'roles' => $this->roleService->getAllRoles(),
        ]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $this->roleService->createRole($request->validated());

        return redirect()
            ->route('super-admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function update(RoleRequest $request, Roles $role): RedirectResponse
    {
        $this->roleService->updateRole($role, $request->validated());

        return redirect()
            ->route('super-admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Roles $role): RedirectResponse
    {
        $deleted = $this->roleService->deleteRole($role);

        if (! $deleted) {
            return redirect()
                ->route('super-admin.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user.');
        }

        return redirect()
            ->route('super-admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}