<?php


use OAuth2\OpenID\Storage\UserClaimsInterface;
use OAuth2\OpenID\Storage\AuthorizationCodeInterface as OpenIDAuthorizationCodeInterface;

/**
 * Simple PDO storage for all storage types
 *
 * NOTE: This class is meant to get users started
 * quickly. If your application requires further
 * customization, extend this class or create your own.
 *
 * NOTE: Passwords are stored in plaintext, which is never
 * a good idea.  Be sure to override this for your application
 *
 * @author Brent Shaffer <bshafs at gmail dot com>
 */
class Oauth_Dbadapter implements
    \OAuth2\Storage\AuthorizationCodeInterface,
    \OAuth2\Storage\AccessTokenInterface,
    \OAuth2\Storage\ClientCredentialsInterface,
    \OAuth2\Storage\UserCredentialsInterface,
    \OAuth2\Storage\RefreshTokenInterface,
    \OAuth2\Storage\JwtBearerInterface,
    \OAuth2\Storage\ScopeInterface,
    \OAuth2\Storage\PublicKeyInterface,
    UserClaimsInterface,
    OpenIDAuthorizationCodeInterface
{
    protected $db;
    protected $config;

    public function __construct($config = array())
    {

		$this->db = Metrodb_Connector::getHandle(_get('oauth_dsn_handle', 'default'));

        $this->config = array_merge(array(
            'client_table'        => 'oauth_clients',
            'access_token_table'  => 'oauth_access_tokens',
            'refresh_token_table' => 'oauth_refresh_tokens',
            'code_table'          => 'oauth_authorization_codes',
            'user_table'          => 'oauth_users',
            'jwt_table'           => 'oauth_jwt',
            'jti_table'           => 'oauth_jti',
            'scope_table'         => 'oauth_scopes',
            'public_key_table'    => 'oauth_public_keys',
        ), $config);
    }

    /* OAuth2\Storage\ClientCredentialsInterface */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
		$finder = \_makeNew('dataitem', $this->config['client_table']);
		$finder->andWhere('client_id', $client_id);
		$result = $finder->findAsArray();

        // make this extensible
        return $result && $result['client_secret'] == $client_secret;
    }

    public function isPublicClient($client_id)
    {
		$finder = \_makeNew('dataitem', $this->config['client_table']);
		$finder->andWhere('client_id', $client_id);
		$result = $finder->findAsArray();
		if (count($result)) {
			$result = $result[0];
		} else {
			return FALSE;
		}

        return empty($result['client_secret']);
    }

    /* OAuth2\Storage\ClientInterface */
    public function getClientDetails($client_id)
    {
		$finder = \_makeNew('dataitem', $this->config['client_table']);
		$finder->andWhere('client_id', $client_id);
		$result = $finder->findAsArray();

		if (count($result)) {
			$result = $result[0];
		} else {
			return FALSE;
		}

		return $result;
    }

    public function setClientDetails($client_id, $client_secret = null, $redirect_uri = null, $grant_types = null, $scope = null, $user_id = null)
    {
		$finder = \_makeNew('dataitem', $this->config['client_table'], 'client_id');
		$finder->set('client_secret', $client_secret);
		$finder->set('redirect_uri', $redirect_uri);
		$finder->set('user_id', $user_id);
		$finder->set('scope', $scope);
		$finder->set('grant_types', $grant_types);

        // if it exists, update it.
        if ($this->getClientDetails($client_id)) {
			$finder->_isNew = FALSE;
        } else {
			$finder->_isNew = TRUE;
        }

        return $finder->save();
    }

    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $details = $this->getClientDetails($client_id);
        if (isset($details['grant_types'])) {
            $grant_types = explode(' ', $details['grant_types']);

            return in_array($grant_type, (array) $grant_types);
        }

        // if grant_types are not defined, then none are restricted
        return true;
    }

    /* OAuth2\Storage\AccessTokenInterface */
    public function getAccessToken($access_token)
    {

		$finder = \_makeNew('dataitem', $this->config['access_token_table']);
		$finder->andWhere('access_token', $access_token);
		$result = $finder->findAsArray();
		if (count($result)) {
			$result = $result[0];
            $result['expires'] = strtotime($result['expires']);
		} else {
			return FALSE;
		}
		return $result;
    }

    public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = gmdate('Y-m-d H:i:s', $expires);

		$dataitem = \_makeNew('dataitem', $this->config['access_token_table'], 'access_token');
		$dataitem->set('access_token', $access_token);
		$dataitem->set('client_id', $client_id);
		$dataitem->set('user_id', $user_id);
		$dataitem->set('expires', $expires);
		$dataitem->set('scope', $scope);

        // if it exists, update it.
        if ($this->getAccessToken($access_token)) {
			$dataitem->_isNew = FALSE;
        } else {
			$dataitem->_isNew = TRUE;
        }

        return $dataitem->save();
    }

    public function unsetAccessToken($access_token)
    {
		$dataitem = \_makeNew('dataitem', $this->config['access_token_table'], 'access_token');
		$dataitem->load($access_token);
		return $dataitem->delete();
    }

    /* OAuth2\Storage\AuthorizationCodeInterface */
    public function getAuthorizationCode($code)
    {
		$finder = \_makeNew('dataitem', $this->config['code_table']);
		$finder->andWhere('authorization_code', $code);
		$result = $finder->findAsArray();

		if (count($result)) {
			$code = $result[0];
            $code['expires'] = strtotime($code['expires']);
		} else {
			return FALSE;
		}

        return $code;
    }

    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null, $id_token = null)
    {
        if (func_num_args() > 6) {
            // we are calling with an id token
            return call_user_func_array(array($this, 'setAuthorizationCodeWithIdToken'), func_get_args());
        }

        // convert expires to datestring
        $expires = gmdate('Y-m-d H:i:s', $expires);

		$dataitem = \_makeNew('dataitem',    $this->config['code_table'], 'authorization_code');
		$dataitem->set('authorization_code', $code);
		$dataitem->set('client_id',          $client_id);
		$dataitem->set('user_id',            $user_id);
		$dataitem->set('redirect_uri',       $redirect_uri);
		$dataitem->set('expires',            $expires);
		$dataitem->set('scope',              $scope);
//		$dataitem->set('id_token',           $id_token);

        // if it exists, update it.
        if ($this->getAuthorizationCode($code)) {
			$dataitem->_isNew = FALSE;
        } else {
			$dataitem->_isNew = TRUE;
        }

        return $dataitem->save();
    }

    private function setAuthorizationCodeWithIdToken($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null, $id_token = null)
    {
        // convert expires to datestring
        $expires = gmdate('Y-m-d H:i:s', $expires);

		$dataitem = \_makeNew('dataitem',    $this->config['code_table'], 'authorization_code');
		$dataitem->set('authorization_code', $code);
		$dataitem->set('client_id',          $client_id);
		$dataitem->set('user_id',            $user_id);
		$dataitem->set('redirect_uri',       $redirect_uri);
		$dataitem->set('expires',            $expires);
		$dataitem->set('scope',              $scope);
		$dataitem->set('id_token',           $id_token);

        // if it exists, update it.
        if ($this->getAuthorizationCode($code)) {
			$dataitem->_isNew = FALSE;
        } else {
			$dataitem->_isNew = TRUE;
        }

        //return $stmt->execute(compact('code', 'client_id', 'user_id', 'redirect_uri', 'expires', 'scope', 'id_token'));
		$dataitem->save();
    }

    public function expireAuthorizationCode($code)
    {
		$dataitem = \_makeNew('dataitem', $this->config['code_table'], 'authorization_code');
		$dataitem->load($code);
		return $dataitem->delete();
    }

    /* OAuth2\Storage\UserCredentialsInterface */
    public function checkUserCredentials($username, $password)
    {
        if ($user = $this->getUser($username)) {
            return $this->checkPassword($user, $password);
        }

        return false;
    }

    public function getUserDetails($username)
    {
        return $this->getUser($username);
    }

    /* UserClaimsInterface */
    public function getUserClaims($user_id, $claims)
    {
        if (!$userDetails = $this->getUserDetails($user_id)) {
            return false;
        }

        $claims = explode(' ', trim($claims));
        $userClaims = array();

        // for each requested claim, if the user has the claim, set it in the response
        $validClaims = explode(' ', self::VALID_CLAIMS);
        foreach ($validClaims as $validClaim) {
            if (in_array($validClaim, $claims)) {
                if ($validClaim == 'address') {
                    // address is an object with subfields
                    $userClaims['address'] = $this->getUserClaim($validClaim, $userDetails['address'] ?: $userDetails);
                } else {
                    $userClaims = array_merge($userClaims, $this->getUserClaim($validClaim, $userDetails));
                }
            }
        }

        return $userClaims;
    }

    protected function getUserClaim($claim, $userDetails)
    {
        $userClaims = array();
        $claimValuesString = constant(sprintf('self::%s_CLAIM_VALUES', strtoupper($claim)));
        $claimValues = explode(' ', $claimValuesString);

        foreach ($claimValues as $value) {
            $userClaims[$value] = isset($userDetails[$value]) ? $userDetails[$value] : null;
        }

        return $userClaims;
    }

    /* OAuth2\Storage\RefreshTokenInterface */
    public function getRefreshToken($refresh_token)
    {
		$finder = \_makeNew('dataitem', $this->config['refresh_token_table'], 'refresh_token');
		$finder->andWhere('refresh_token', $refresh_token);
		$result = $finder->findAsArray();
		if (count($result)) {
			$result = $result[0];
            $result['expires'] = strtotime($result['expires']);
		} else {
			return FALSE;
		}
        return $result;
    }

    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = gmdate('Y-m-d H:i:s', $expires);

		$dataitem = \_makeNew('dataitem', $this->config['refresh_token_table'], 'refresh_token');
		$dataitem->set('refresh_token', $refresh_token);
		$dataitem->set('client_id', $client_id);
		$dataitem->set('user_id', $user_id);
		$dataitem->set('expires', $expires);
		$dataitem->set('scope', $scope);
        return $dataitem->save();
    }

    public function unsetRefreshToken($refresh_token)
    {
		$dataitem = \_makeNew('dataitem', $this->config['refresh_token_table'], 'refresh_token');
		$dataitem->load($refresh_token);
		return $dataitem->delete();
    }

    // plaintext passwords are bad!  Override this for your application
    protected function checkPassword($user, $password)
    {
        return $user['password'] == sha1($password);
    }

    public function getUser($username)
    {
        $stmt = $this->db->query($sql = sprintf('SELECT * from %s where username=\'%s\'', $this->config['user_table'], $username));

		$this->db->nextRecord();
        if (!$userInfo = $this->db->record) {
            return false;
        }

        // the default behavior is to use "username" as the user_id
        return array_merge(array(
            'user_id' => $username
        ), $userInfo);
    }

    public function setUser($username, $password, $firstName = null, $lastName = null)
    {
        // do not store in plaintext
        $password = sha1($password);

        // if it exists, update it.
        if ($this->getUser($username)) {
            $stmt = $this->db->prepare($sql = sprintf('UPDATE %s SET password=:password, first_name=:firstName, last_name=:lastName where username=:username', $this->config['user_table']));
        } else {
            $stmt = $this->db->prepare(sprintf('INSERT INTO %s (username, password, first_name, last_name) VALUES (:username, :password, :firstName, :lastName)', $this->config['user_table']));
        }

        return $stmt->execute(compact('username', 'password', 'firstName', 'lastName'));
    }

    /* ScopeInterface */
    public function scopeExists($scope)
    {
        $scope = explode(' ', $scope);
        $whereIn = "'".implode('\',\'', $scope)."'";
        $result = $this->db->query(sprintf('SELECT count(scope) as count FROM %s WHERE scope IN (%s)', $this->config['scope_table'], $whereIn));

		while ($this->db->nextRecord()) {
			$result = $this->db->record;
		}
        if ($result) {
            return $result['count'] == count($scope);
        }
        return false;
    }

    public function getDefaultScope($client_id = null)
    {
        $x = $this->db->query(sprintf('SELECT scope FROM %s WHERE is_default=1', $this->config['scope_table']));

		$result = [];
		while ($this->db->nextRecord()) {
			$result[] = $this->db->record;
		}

        if ($result) {
            $defaultScope = array_map(function ($row) {
                return $row['scope'];
            }, $result);

            return implode(' ', $defaultScope);
        }

        return null;
    }

    /* JWTBearerInterface */
    public function getClientKey($client_id, $subject)
    {
        $stmt = $this->db->query($sql = sprintf('SELECT public_key from %s where client_id=\'%s\' AND subject=\'%s\'', $this->config['jwt_table'], $client_id, $subject));
		$this->db->nextRecord();
        return $this->db->record;
    }

    public function getClientScope($client_id)
    {
        if (!$clientDetails = $this->getClientDetails($client_id)) {
            return false;
        }

        if (isset($clientDetails['scope'])) {
            return $clientDetails['scope'];
        }

        return null;
    }

	//TODO: finish migration to metrodb
    public function getJti($client_id, $subject, $audience, $expires, $jti)
    {
		$finder = \_makeNew('dataitem', $this->config['jti_table']);
		$finder->andWhere('issuer',   $client_id);
		$finder->andWhere('subject',  $subject);
		$finder->andWhere('audience', $audience);
		$finder->andWhere('expires',  $expires);
		$finder->andWhere('jti',      $jti);
		$result = $finder->findAsArray();

		if (count($result)) {
			$result = $result[0];
		}
        if ($result) {
            return array(
                'issuer' => $result['issuer'],
                'subject' => $result['subject'],
                'audience' => $result['audience'],
                'expires' => $result['expires'],
                'jti' => $result['jti'],
            );
        }
        return null;
    }

    public function setJti($client_id, $subject, $audience, $expires, $jti)
    {
		$dataitem = \_makeNew('dataitem', $this->config['jti_table'], null);
		$dataitem->andWhere('issuer',   $client_id);
		$dataitem->andWhere('subject',  $subject);
		$dataitem->andWhere('audience', $audience);
		$dataitem->andWhere('expires',  $expires);
		$dataitem->andWhere('jti',      $jti);
		$result = $dataitem->save();
		return $result;
    }

    /* PublicKeyInterface */
    public function getPublicKey($client_id = null)
    {
		$finder = \_makeNew('dataitem', $this->config['public_key_table'], null);
		$finder->andWhere('client_id',   $client_id);
		$finder->orWhereSub('client_id',   NULL, 'IS');
		$finder->sort('client_id', 'DESC');
		$result = $finder->findAsArray();

		if (count($result)) {
			$result = $result[0];
		}

        if ($result) {
            return $result['public_key'];
        }
    }

    public function getPrivateKey($client_id = null)
    {

		$finder = \_makeNew('dataitem', $this->config['public_key_table'], null);
		$finder->andWhere('client_id',   $client_id);
		$finder->orWhereSub('client_id',   NULL, 'IS');
		$finder->sort('client_id', 'DESC');
		$result = $finder->findAsArray();

		if (count($result)) {
			$result = $result[0];
		}

        if ($result) {
            return $result['private_key'];
        }
    }

    public function getEncryptionAlgorithm($client_id = null)
    {
		$finder = \_makeNew('dataitem', $this->config['public_key_table'], null);
		$finder->_cols[] = 'encryption_algorithm';
		$finder->andWhere('client_id',   $client_id);
		$finder->orWhereSub('client_id',   NULL, 'IS');
		$finder->sort('client_id', 'DESC');
		$result = $finder->findAsArray();

		if (count($result)) {
			$result = $result[0];
		}

        if ($result) {
            return $result['encryption_algorithm'];
        }

        return 'RS256';
    }

    /**
     * DDL to create OAuth2 database and tables for PDO storage
     *
     * @see https://github.com/dsquier/oauth2-server-php-mysql
     */
    public function getBuildSql($dbName = 'oauth2_server_php')
    {
        $sql = "
        CREATE TABLE {$this->config['client_table']} (
          client_id             VARCHAR(80)   NOT NULL,
          client_secret         VARCHAR(80)   NOT NULL,
          redirect_uri          VARCHAR(2000),
          grant_types           VARCHAR(80),
          scope                 VARCHAR(4000),
          user_id               VARCHAR(80),
          PRIMARY KEY (client_id)
        );

        CREATE TABLE {$this->config['access_token_table']} (
          access_token         VARCHAR(40)    NOT NULL,
          client_id            VARCHAR(80)    NOT NULL,
          user_id              VARCHAR(80),
          expires              TIMESTAMP      NOT NULL,
          scope                VARCHAR(4000),
          PRIMARY KEY (access_token)
        );

        CREATE TABLE {$this->config['code_table']} (
          authorization_code  VARCHAR(40)    NOT NULL,
          client_id           VARCHAR(80)    NOT NULL,
          user_id             VARCHAR(80),
          redirect_uri        VARCHAR(2000),
          expires             TIMESTAMP      NOT NULL,
          scope               VARCHAR(4000),
          id_token            VARCHAR(1000),
          PRIMARY KEY (authorization_code)
        );

        CREATE TABLE {$this->config['refresh_token_table']} (
          refresh_token       VARCHAR(40)    NOT NULL,
          client_id           VARCHAR(80)    NOT NULL,
          user_id             VARCHAR(80),
          expires             TIMESTAMP      NOT NULL,
          scope               VARCHAR(4000),
          PRIMARY KEY (refresh_token)
        );

        CREATE TABLE {$this->config['user_table']} (
          username            VARCHAR(80),
          password            VARCHAR(80),
          first_name          VARCHAR(80),
          last_name           VARCHAR(80),
          email               VARCHAR(80),
          email_verified      BOOLEAN,
          scope               VARCHAR(4000)
        );

        CREATE TABLE {$this->config['scope_table']} (
          scope               VARCHAR(80)  NOT NULL,
          is_default          BOOLEAN,
          PRIMARY KEY (scope)
        );

        CREATE TABLE {$this->config['jwt_table']} (
          client_id           VARCHAR(80)   NOT NULL,
          subject             VARCHAR(80),
          public_key          VARCHAR(2000) NOT NULL
        );

        CREATE TABLE {$this->config['jti_table']} (
          issuer              VARCHAR(80)   NOT NULL,
          subject             VARCHAR(80),
          audiance            VARCHAR(80),
          expires             TIMESTAMP     NOT NULL,
          jti                 VARCHAR(2000) NOT NULL
        );

        CREATE TABLE {$this->config['public_key_table']} (
          client_id            VARCHAR(80),
          public_key           VARCHAR(2000),
          private_key          VARCHAR(2000),
          encryption_algorithm VARCHAR(100) DEFAULT 'RS256'
        )
";

        return $sql;
    }
}
