<?php

namespace App\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use App\Role;
use App\RoleUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller {
	public function updateUser(Request $request) {
		if (!$request->has('role')) {
			return response('Attribute role has to be sent in the request', 400);
		}
		if (!in_array($request->role, [
			Role::ROLE_CUSTOMER,
			Role::ROLE_PROVIDER,
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

	public function updateSatus(Request $request, $id) {
		$request->validate([
			'status' => ['required', 'string', Rule::in(User::getAllStatus()),
			]]);

		try {

			$user = User::query()->findOrFail($id);
			$user->status = $request->input('status');
			$user->is_block = in_array($request->input('status'), [
				User::STATUS_BLOCK, User::STATUS_INACTIVE,
			]) ? true : false;

			$user->save();

			return response()->json(['success' => true, 'message' => 'User status updated'], 201);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage(), 'success' => false], 401);
		}
	}

	public function getAllStatus() {
		$status = User::getAllStatus();

		$displayStatus = [];

		foreach ($status as $value) {
			$displayStatus[$value] = ucwords(str_replace("_", " ", $value));
		}

		return response()->json(['success' => true, 'data' => $displayStatus], 200);

	}
}