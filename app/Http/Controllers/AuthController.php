<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\AuthenticateUser;
use App\AuthenticateUserListener;

class AuthController extends Controller implements AuthenticateUserListener
{
	/**
	 * @param AuthenticateUser $authenticateUser
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function lineLogin(AuthenticateUser $authenticateUser, Request $request)
	{
		$hasCode = $request->has('code');

		return $authenticateUser->execute($hasCode, $this);
	}

	/**
	 * When a user has successfully been logged in...
	 *
	 * @param $user
	 * @return \Illuminate\Routing\Redirector
	 */
	public function userHasLoggedIn($user)
	{
		return redirect('/');
	}
}