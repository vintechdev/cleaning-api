<?php

namespace App\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use App\Role;
use App\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateUser(Request $request) {
        if (!$request->has('role')) {
            return response('Attribute role has to be sent in the request', 400);
        }
        if (!in_array($request->role, [
            Role::ROLE_CUSTOMER,
            Role::ROLE_PROVIDER
        ])) {
            return response('You are forbidden from adding that role', 403);
        }

        $role = $request->role;
        $user = Auth::user();
        $user_id = $user->id;
        $rl = new Role();
        $rls = $rl->where('name', $role)->first();
        //add another role for same email
        $RoleUser = RoleUser::firstOrNew(['user_id' => $user_id, 'role_id' => $rls->id]);
        $RoleUser->user_id = $user_id;
        $RoleUser->save();

        if ($role == 'provider') {
            $user->providertype = $request->get('provider_type');
            $user->save();
        }

        return response()->json(['success' => 'User updated with new role'], 201);
    }
}