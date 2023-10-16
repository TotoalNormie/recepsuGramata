<?php
	require_once("session_handler.php");
	require_once("utility.php");

	if($_SERVER["REQUEST_METHOD"] === "POST")
	{
		if(isset($_POST["user"]) && isset($_POST["pass"]))
		{
			header("Location: /index.php");

			$expire_time = time() + 60 * 60 * 10; // 10 hours
			setcookie("token",
			    (new Session(
			        GenerateUserID($_POST["user"], $_POST["pass"]),
			        $expire_time,
					SessionAuthority::USER
				))->ToToken(),
			$expire_time);
			
			
			exit(CreateResponse("Success", "Session Created Succesfully"));
		}
		else
		{
			exit(CreateResponse("Failure", "Missing Parameters"));
		}
	}
	else
	{
		exit(CreateResponse("Failure", "Bad Method"));
	}
?>