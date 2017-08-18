<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Initial extends AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE CHARACTER SET 'utf8';");
        $this->execute("ALTER DATABASE COLLATE='utf8_general_ci';");
		$this->table_oauth_access_tokens();
		$this->table_oauth_authorization_codes();
		$this->table_oauth_clients();
		$this->table_oauth_jti();
		$this->table_oauth_jwt();
		$this->table_oauth_public_keys();
		$this->table_oauth_refresh_tokens();
		$this->table_oauth_scopes();
		$this->table_oauth_users();
		$this->table_account_person();
		$this->table_password_reset();
		$this->table_user_group_link();
		$this->table_user_login();
		$this->table_user_sess();
	}

	public function table_oauth_access_tokens() {
		$table = $this->table("oauth_access_tokens",
			['id' => false,
			'primary_key' => ["access_token"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('access_token',
			'string',
			['null' => false,
			'limit' => 40,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]);
        $table->addColumn('client_id',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'access_token']);
        $table->addColumn('user_id',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'client_id']);
        $table->addColumn('expires',
			'timestamp',
			['null' => false,
			'default' => 'CURRENT_TIMESTAMP',
			'update' => 'CURRENT_TIMESTAMP',
			'after' => 'user_id']);
        $table->addColumn('scope',
			'string',
			['null' => true,
			'limit' => 4000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'expires']);
        $table->save();
	}

	public function table_oauth_authorization_codes() {
        $table = $this->table("oauth_authorization_codes",
			['id' => false,
			'primary_key' => ["authorization_code"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('authorization_code',
			'string',
			['null' => false,
			'limit' => 40,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]);
        $table->addColumn('client_id',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'authorization_code']);
        $table->addColumn('user_id',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'client_id']);
        $table->addColumn('redirect_uri',
			'string',
			['null' => true,
			'limit' => 2000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'user_id']);
        $table->addColumn('expires',
			'timestamp',
			['null' => false,
			'default' => 'CURRENT_TIMESTAMP',
			'update' => 'CURRENT_TIMESTAMP',
			'after' => 'redirect_uri']);
        $table->addColumn('scope',
			'string',
			['null' => true,
			'limit' => 4000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'expires']);
        $table->addColumn('id_token',
			'string',
			['null' => true,
			'limit' => 1000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'scope']);
        $table->save();
	}

	public function table_oauth_clients() {
        $table = $this->table("oauth_clients",
			['id' => false,
			'primary_key' => ["client_id"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('client_id',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]);
        $table->addColumn('client_secret',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'client_id']);
        $table->addColumn('redirect_uri',
			'string',
			['null' => true,
			'limit' => 2000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'client_secret']);
        $table->addColumn('grant_types',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'redirect_uri']);
        $table->addColumn('scope',
			'string',
			['null' => true,
			'limit' => 4000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'grant_types']);
        $table->addColumn('user_id',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'scope']);
        $table->addColumn('client_name',
			'string',
			['null' => true,
			'default' => '',
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'user_id']);
        $table->save();
	}

	public function table_oauth_jti() {
        $table = $this->table("oauth_jti",
			['engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->save();
        $table->addColumn('issuer',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]
		)->update();
        $table->addColumn('subject',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'issuer']
		)->update();
        $table->addColumn('audiance',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'subject']
		)->update();
        $table->addColumn('expires',
			'timestamp',
			['null' => false,
			'default' => 'CURRENT_TIMESTAMP',
			'update' => 'CURRENT_TIMESTAMP',
			'after' => 'audiance']
		)->update();
        $table->addColumn('jti',
			'string',
			['null' => false,
			'limit' => 2000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'expires']
		)->update();
        $table->save();
	}

	public function table_oauth_jwt() {
        $table = $this->table("oauth_jwt",
			['engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->save();
        $table->addColumn('client_id',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]
		)->update();
        $table->addColumn('subject',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'client_id']
		)->update();
        $table->addColumn('public_key',
			'string',
			['null' => false,
			'limit' => 2000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'subject']
		)->update();
        $table->save();
	}

	public function table_oauth_public_keys() {
        $table = $this->table("oauth_public_keys",
			['engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->save();
        $table->addColumn('client_id',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]
		)->update();
        $table->addColumn('public_key',
			'string',
			['null' => true,
			'limit' => 2000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'client_id']
		)->update();
        $table->addColumn('private_key',
			'string',
			['null' => true,
			'limit' => 2000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'public_key']
		)->update();
        $table->addColumn('encryption_algorithm',
			'string',
			['null' => true,
			'default' => 'RS256',
			'limit' => 100,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'private_key']
		)->update();
        $table->save();
	}

	public function table_oauth_refresh_tokens() {
        $table = $this->table("oauth_refresh_tokens",
			['id' => false,
			'primary_key' => ["refresh_token"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('refresh_token',
			'string',
			['null' => false,
			'limit' => 40,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]);
        $table->addColumn('client_id',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'refresh_token']);
        $table->addColumn('user_id',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'client_id']);
        $table->addColumn('expires',
			'timestamp',
			['null' => false,
			'default' => 'CURRENT_TIMESTAMP',
			'update' => 'CURRENT_TIMESTAMP',
			'after' => 'user_id']);
        $table->addColumn('scope',
			'string',
			['null' => true,
			'limit' => 4000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'expires']);
        $table->save();
	}

	public function table_oauth_scopes() {
        $table = $this->table("oauth_scopes",
			['id' => false,
			'primary_key' => ["scope"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('scope',
			'string',
			['null' => false,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]);
        $table->addColumn('is_default',
			'boolean',
			['null' => true,
			'limit' => MysqlAdapter::INT_TINY,
			'precision' => 3,
			'after' => 'scope']);
        $table->save();
	}

	public function table_oauth_users() {
        $table = $this->table("oauth_users",
			['engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->save();
        $table->addColumn('username',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8"]
		)->update();
        $table->addColumn('password',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'username']
		)->update();
        $table->addColumn('first_name',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'password']
		)->update();
        $table->addColumn('last_name',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'first_name']
		)->update();
        $table->addColumn('email',
			'string',
			['null' => true,
			'limit' => 80,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'last_name']
		)->update();
        $table->addColumn('email_verified',
			'boolean',
			['null' => true,
			'limit' => MysqlAdapter::INT_TINY,
			'precision' => 3,
			'after' => 'email']
		)->update();
        $table->addColumn('scope',
			'string',
			['null' => true,
			'limit' => 4000,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'email_verified']
		)->update();
        $table->save();
	}

	public function table_account_person() {
        $table = $this->table("account_person",
			['id' => false,
			'primary_key' => ["account_person_id"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('account_person_id',
			'integer',
			['null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'identity' => 'enable']);
        $table->addColumn('created_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'account_person_id']);
        $table->addColumn('updated_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'created_on']);
        $table->addColumn('firstname',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'updated_on']);
        $table->addColumn('lastname',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'firstname']);
        $table->addColumn('email',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'lastname']);
        $table->addColumn('user_login_id',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'after' => 'email']);
        $table->save();
        if($this->table('account_person')->hasIndex('user_login_id')) {
            $this->table("account_person")->removeIndexByName('user_login_id');
        }
        $this->table("account_person")->addIndex(['user_login_id'],
			['name' => "user_login_id",
			'unique' => false])->save();
	}

	public function table_password_reset() {
        $table = $this->table("password_reset",
			['id' => false,
			'primary_key' => ["password_reset_id"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('password_reset_id',
			'integer',
			['null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'identity' => 'enable']);
        $table->addColumn('created_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'password_reset_id']);
        $table->addColumn('updated_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'created_on']);
        $table->addColumn('ip',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'updated_on']);
        $table->addColumn('token',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'ip']);
        $table->addColumn('email',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'token']);
        $table->addColumn('used_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'email']);
        $table->save();
	}

	public function table_user_group_link() {
        $table = $this->table("user_group_link",
			['id' => false,
			'primary_key' => ["user_group_link_id"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('user_group_link_id',
			'integer',
			['null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'identity' => 'enable']);
        $table->addColumn('created_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'user_group_link_id']);
        $table->addColumn('updated_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'created_on']);
        $table->addColumn('user_group_id',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'updated_on']);
        $table->addColumn('user_login_id',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'user_group_id']);
        $table->addColumn('activeOn',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'user_login_id']);
        $table->save();
	}

	public function table_user_login() {
        $table = $this->table("user_login",
			['id' => false,
			'primary_key' => ["user_login_id"],
			'engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->addColumn('user_login_id',
			'integer',
			['null' => false,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'identity' => 'enable']);
        $table->addColumn('created_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'user_login_id']);
        $table->addColumn('updated_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'created_on']);
        $table->addColumn('email',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'updated_on']);
        $table->addColumn('locale',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'email']);
        $table->addColumn('tzone',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'locale']);
        $table->addColumn('username',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'tzone']);
        $table->addColumn('password',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'username']);
        $table->addColumn('validation_token',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'password']);
        $table->addColumn('login_referrer',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'validation_token']);
        $table->addColumn('reg_referrer',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'login_referrer']);
        $table->addColumn('id_provider_token',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'reg_referrer']);
        $table->addColumn('reg_ip_addr',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'id_provider_token']);
        $table->addColumn('login_ip_addr',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'reg_ip_addr']);
        $table->addColumn('id_provider',
			'string',
			['null' => false,
			'default' => 'self',
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'login_ip_addr']);
        $table->addColumn('reg_date',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'id_provider']);
        $table->addColumn('login_date',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'reg_date']);
        $table->addColumn('active_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'login_date']);
        $table->save();
        if($this->table('user_login')->hasIndex('username_2')) {
            $this->table("user_login")->removeIndexByName('username_2');
        }
        $this->table("user_login")->addIndex(['username','id_provider'],
			['name' => "username_2",
			'unique' => true])->save();
        if($this->table('user_login')->hasIndex('username')) {
            $this->table("user_login")->removeIndexByName('username');
        }
        $this->table("user_login")->addIndex(['username','password'],
			['name' => "username",
			'unique' => false])->save();
	}

	public function table_user_sess() {
        $table = $this->table("user_sess",
			['engine' => "InnoDB",
			'encoding' => "utf8",
			'collation' => "utf8_general_ci",
			'comment' => ""]);
        $table->save();
        $table->addColumn('created_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false]
		)->update();
        $table->addColumn('updated_on',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'signed' => false,
			'after' => 'created_on']
		)->update();
        $table->addColumn('user_sess_key',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'updated_on']
		)->update();
        $table->addColumn('touch_time',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'after' => 'user_sess_key']
		)->update();
        $table->addColumn('data',
			'text',
			['null' => true,
			'limit' => MysqlAdapter::TEXT_LONG,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'touch_time']
		)->update();
        $table->addColumn('ip_addr',
			'string',
			['null' => true,
			'limit' => 255,
			'collation' => "utf8_general_ci",
			'encoding' => "utf8",
			'after' => 'data']
		)->update();
        $table->addColumn('last_touch_time',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'after' => 'ip_addr']
		)->update();
        $table->addColumn('auth_time',
			'integer',
			['null' => true,
			'limit' => MysqlAdapter::INT_REGULAR,
			'precision' => 10,
			'after' => 'last_touch_time']
		)->update();
        $table->save();
    }
}
