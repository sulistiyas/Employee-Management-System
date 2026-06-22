<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Roles;
use App\Models\User;
use App\Services\SuperAdmin\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(Request $request): View
    {
        $users = $this->userService->getAllUsers($request->query('search'));
        $roles = Roles::orderBy('name')->get();

        if ($request->ajax()) {
            return view('super-admin.users.table', [
                'users' => $users,
            ]);
        }

        return view('super-admin.users.index', [
            'users' => $users,
            'roles' => $roles,
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
}
