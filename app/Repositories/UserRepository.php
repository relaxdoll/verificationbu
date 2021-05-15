<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
	/**
	 * @param $userData
	 * @return static
	 */
	public function findUserOrFail($userData)
	{
		if (auth()->check())
		{
			auth()->user()->update([
				'avatar' => $userData->avatar,
				'lineId' => $userData->id,
			]);

			return auth()->user();
		} else
		{
			$user = User::where('lineId', $userData->id)->first();

			if (!is_null($user))
			{
				return $user;
			}else{

				return User::find(0);
			}

		}

	}

	public function notValid()
	{

		return redirect('home');
	}
}