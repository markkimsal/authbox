<?php

use Phinx\Seed\AbstractSeed;

class TestClientSeed extends AbstractSeed
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

		$this->installClient();
	}


	//-------------------------------------+-------------+-------+---------+-------------+
	//| testclient |               | http://mark.dev.local/authbox/test/implicit/ https://www.getpostman.com/oauth2/callback http://mark.dev.local/authbox/test/implicit http://mark.dev.local/authbox/test/code | NULL        | NULL  | NULL    | Test Client |
	//
	public function installClient()
	{
		$data = [
			[
				'client_id'     => 'testclient',
				'client_secret' => '',
				'redirect_uri'  => 'http://localhost/authbox/test/implicit/ https://www.getpostman.com/oauth2/callback http://localhost/authbox/test/implicit http://localhost/authbox/test/code',
				'grant_types'   => NULL,
				'scope'         => NULL,
				'user_id'       => NULL,
				'client_name'   => 'Test Client'
			],
		];
		$this->insert('oauth_clients', $data);
	}
}
