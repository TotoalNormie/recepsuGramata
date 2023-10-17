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
				if($DB->ValidUserLogin($_POST["user"], $user_id))
				{
					header("Location: /");

					$expire_time = time() + 60 * 60 * 10; // 10 hours
					setcookie("token",
						(new Session(
							$user_id,
							$expire_time,
							SessionAuthority::USER
						))->ToToken(),
					$expire_time); // idk why its forcing me to specify the "/"

					exit(CreateResponse("Success", "Session Created Succesfully"));
				}
				else
				{
					exit(CreateResponse("Failure", "Bad Login", "+", $_POST["user"] . "\n" . $user_id));
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