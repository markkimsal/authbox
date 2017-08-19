<?php

use Phinx\Seed\AbstractSeed;

class StartingAccountSeed extends AbstractSeed
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

		$this->installUser();
		$this->installAccount();
	}


	public function installUser()
	{
		$defaults =  [
				'created_on'    => time(),
				'updated_on'    => time(),
				'active_on'     => time(),
				'reg_date'      => time(),
				'id_provider'   => 'local',
				'locale'        => 'en_US',
				'tzone'         => 'America/New_York',
		];


		$data = [
			 $defaults + [
				'user_login_id' => 1,
				'email'         => 'root@example.com',
				'username'      => 'root',
				'password'      => '$2y$10$fOYIvvgsn4aBJeuAXu8Zm.D6mpPgVKf9Po26jh8GleznS9fAiyuwG',
			],
		];

		$this->insert('user_login', $data);
	}

	public function installAccount()
	{
		//| account_person_id | created_on | updated_on | firstname  | lastname | email  | user_login_id
		$defaults =  [
				'created_on'    => time(),
				'updated_on'    => time(),
		];

		$data = [
			$defaults + [
				'account_person_id' => 1,
				'firstname'         => 'Root',
				'lastname'          => 'User',
				'email'             => 'root@authbox.example.com',
				'user_login_id'     => 1,
			],
		];
		$this->insert('account_person', $data);
	}
}
