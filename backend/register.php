<?php
	require_once("session_library.php");
	require_once("database_interface.php");
	require_once("utility.php");

	if($_SERVER["REQUEST_METHOD"] === "POST")
	{
		try
		{		
			if(isset($_POST["user"]) && isset($_POST["pass"]))
			{
				$DB = new RecipeDatabase();
				$user_id = GenerateUserID($_POST["user"], $_POST["pass"]);
				if(!$DB->UserExists($_POST["user"]))
				{
					$user_id = GenerateUserID($_POST["user"], $_POST["pass"]);
					$DB->RegisterUser($_POST["user"], $user_id); // if this fails an exception will be raised

					header("Location: /");
										
					$expire_time = time() + 60 * 60 * 10; // 10 hours
					setcookie("token",
						(new Session(
							$user_id,
							$expire_time,
							SessionAuthority::USER
						))->ToToken(),
					$expire_time);

					exit(CreateResponse("Success", "Account Registered Succesfully"));
				}
				else
				{
					exit(CreateResponse("Failure", "Bad Username"));
				}
				
			}
			else
			{
				exit(CreateResponse("Failure", "Missing Parameters"));
			}
		}
		catch(Exception $e)
		{
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->getMessage()));
		}
	}
	else
	{
		exit(CreateResponse("Failure", "Bad Method"));
	}
?>