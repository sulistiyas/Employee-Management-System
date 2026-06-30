<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Roles;
use App\Services\SuperAdmin\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService) {}

    public function index(Request $request): View
    {
        $roles = $this->roleService->getAllRoles(
            search: $request->query('search'),
            sort: $request->query('sort'),
            dir: $request->query('dir', 'asc'),
            perPage: (int) $request->query('per_page', 10)
        );

        if ($request->ajax()) {
            return view('super-admin.roles.table', [
                'roles' => $roles,
            ]);
        }

        return view('super-admin.roles.index', [
            'roles' => $roles,
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

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $roleIds = $request->input('role_ids', []);
        $deletedCount = $this->roleService->deleteManyRoles($roleIds);

        if ($deletedCount === 0) {
            return redirect()
                ->route('super-admin.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user.');
        }

        return redirect()
            ->route('super-admin.roles.index')
            ->with('success', "{$deletedCount} role berhasil dihapus.");
    }
}