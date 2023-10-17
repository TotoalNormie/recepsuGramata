<?php

	$___user_id_salt = hash("sha512", "very unique salt");
	$___user_stamp = hash("sha256", "mega secure user stamp");
	$___admin_stamp = hash("sha256", "super secure admin hash");
	$___encryption_key = hash("sha256", "mega super duper secure key (secret)");
	$___encryption_iv = substr(hash("sha512", "this doesnt really matter"), 0, 16);

	enum SessionAuthority
	{
		case USER;
		case ADMIN;
	}

	function StampToEnum($stamp)
	{
		global $___user_stamp;
		global $___admin_stamp;

		switch($stamp)
		{
			case $___user_stamp:
				return SessionAuthority::USER;
			case $___admin_stamp:
				return SessionAuthority::ADMIN;

			default:
				return false;
		}
	}

	function EnumToStamp($authority)
	{
		global $___user_stamp;
		global $___admin_stamp;

		switch($authority)
		{
			case SessionAuthority::USER:
				return $___user_stamp;
			case SessionAuthority::ADMIN:
				return $___admin_stamp;
			
			default:
				return false;
		}
	}

	function GenerateUserID($username, $password)
	{
		global $___user_id_salt;

		return hash("sha256", $username . $___user_id_salt . $password);
	}

	class Session
	{
		public $user_id;
		public $expire_time;
		public $stamp;
		public $authority;

		public function __construct($user_id, $expire_time, $authority)
		{
			global $___user_stamp;
			global $___admin_stamp;			

			$this->user_id = $user_id;
			$this->expire_time = $expire_time;
			$this->authority = $authority;			

			$this->stamp = EnumToStamp($authority);
		}

		public function ToToken()
		{
			global $___encryption_key;
			global $___encryption_iv;
	
			return openssl_encrypt($this->user_id . "|" . $this->expire_time . "|" . $this->stamp, "aes-256-cbc", $___encryption_key, 0, $___encryption_iv);
		}

		static public function FromToken($token)
		{
			global $___encryption_key;
			global $___encryption_iv;
	
			$data = openssl_decrypt($token, "aes-256-cbc", $___encryption_key, 0, $___encryption_iv);
			if($data === false)
				return false;
			
			$data = explode("|", $data);
			if(intval($data[1]) <= time())
				return false;	
			
			$authority = StampToEnum($data[2]);
			
			return new Session($data[0], $data[1], $authority);
		}

		public function HasAuthority($authority)
		{
			return $this->authority === $authority;
		}
	}


	// generic session handling
	
	function HandleSession()
	{
		$failureMessage = null;

		$session = false;
		if(isset($_COOKIE["token"]))
		{
			if(!($session = Session::FromToken($_COOKIE["token"])))
			{
				setcookie("token", "", 1);
				$failureMessage = "Authentication Failed";
			}
		}
		else
		{
			$failureMessage = "Please Authenticate Yourself";
		}
		
		if($failureMessage)
		{
			header("Location: /index.php");
			exit(CreateResponse("Failure", $failureMessage));
		}

		return $session;
	}

	function HandleSessionSoft()
	{
		$session = false;
		if(isset($_COOKIE["token"]))
			if(!($session = Session::FromToken($_COOKIE["token"])))
				setcookie("token", "", 1);

		return $session;
	}
?>