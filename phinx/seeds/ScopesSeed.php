<?php

use Phinx\Seed\AbstractSeed;

class ScopesSeed extends AbstractSeed
{
	/**
	 * Run Method.
	 *
	 * Write your database seeder using this method.
	 *
	 * More information on writing seeders is available here:
	 * http://docs.phinx.org/en/latest/seeding.html
	 */
	public function run() {

		$this->installScopes();
	}


	public function installScopes()
	{
		$data = [
			[
				'is_default' => 1,
				'scope'      => 'profile',
			],
			[
				'is_default' => 0,
				'scope'      => 'email',
			],
			[
				'is_default' => 0,
				'scope'      => 'openid',
			],

		];

		$this->insert('oauth_scopes', $data);
	}
}
