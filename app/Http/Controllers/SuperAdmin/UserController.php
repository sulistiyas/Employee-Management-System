<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Roles;
use App\Models\User;
use App\Services\SuperAdmin\EmployeeService;
use App\Services\SuperAdmin\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
        private EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $users = $this->userService->getAllUsers(
            search: $request->query('search'),
            roleId: $request->query('role') ? (int) $request->query('role') : null,
            isActive: $request->query('active'),
            sort: $request->query('sort'),
            dir: $request->query('dir', 'asc'),
            perPage: (int) $request->query('per_page', 10)
        );
        $roles = Roles::orderBy('name')->get();
        $availableEmployees = $this->employeeService->getAvailableEmployees();

        if ($request->ajax()) {
            return view('super-admin.users.table', [
                'users' => $users,
            ]);
        }

        return view('super-admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'availableEmployees' => $availableEmployees,
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->userService->createUser($request->validated());

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->deleteUser($user);

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $userIds = $request->input('user_ids', []);
        $deletedCount = $this->userService->deleteManyUsers($userIds);

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', "{$deletedCount} user berhasil dihapus.");
    }
}