<?php
	require_once("session_library.php");
	$user_session = HandleSessionSoft();

	require_once("database_interface.php");
	require_once("utility.php");

	$DB = new RecipeDatabase();

	if($_SERVER["REQUEST_METHOD"] === "GET")
	{
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));

			$GetData = GetAllRequestData();

			if(isset($GetData["recipe_id"]))
			{
				$isOwner = $DB->IsRecipeOwner($GetData["recipe_id"], $user_session->user_id);
				if($isOwner === null)
					exit(CreateResponse("Failure", "Recipe Not Found"));
				else
					exit(CreateResponse("Success", "Ownership Retrieved Succesfully", "data", $isOwner));
			}
			else
			{
				exit(CreateResponse("Failure", "Please Provide a Recipe ID"));
			}
		}
		catch(Exception $e)
		{
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->getMessage()));
		}
	}	
	else
	{
		exit(CreateResponse("Failure", "Bad Request Method"));
	}
?>