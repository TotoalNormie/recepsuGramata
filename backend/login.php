<?php
	require_once("session_handler.php");
	require_once("recipe");
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
					$expire_time);

					exit(CreateResponse("Success", "Session Created Succesfully"));
				}
				else
				{
					exit("Failure", "Bad Login");
				}
				
			}
			else
			{
				exit(CreateResponse("Failure", "Missing Parameters"));
			}
		}
		catch(Exception $e)
		{
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->string));
		}
	}
	else
	{
		exit(CreateResponse("Failure", "Bad Method"));
	}
?>