<?php
	require_once("session_library.php");
	$user_session = HandleSessionSoft();

	require_once("database_interface.php");
	require_once("utility.php")

	$request_method = $_SERVER["REQUEST_METHOD"];
	$DB = new RecipeDatabase();

	if($request_method === "GET")
	{
		try
		{			
			if(isset($request_method["id"]))
			{
				$recipe = $DB->GetRecipeByID($_REQUEST["id"]);
				if($recipe)
				{
					$DB->IncrementViewsByID($_REQUEST["id"]);
					exit(CreateResponse("Success", "Recipe Retrieved Succesfully", "data", $recipe));
				}
				else
				{
					exit(CreateResponse("Failure", "Recipe Not Found"));
				}
			}
			else
			{
				exit(CreateResponse("Success", "Recipes Retrieved Succesfully", "data", $DB->ListRecipes()));
			}
		}
		catch(Exception $e)
		{
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->string));
		}
	}
	else if($request_method === "POST") // update recipe
	{
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));

			if(isset($_REQUEST["id"]) && (isset($_REQUEST["title"]) || isset($_REQUEST["description"]) || isset($_REQUEST["image_url"]) || isset($_REQUEST["ingredient_json"])))
			{
				if($DB->IsRecipeOwner($_REQUEST["id"], $user_session->user_id))
				{
					$DB->UpdateRecipeByID($_REQUEST["id"], $_REQUEST["title"] ?? null, $_REQUEST["description"] ?? null, = $_REQUEST["image_url"] ?? null, $_REQUEST["ingredient_json"] ?? null);
					exit(CreateResponse("Success", "Recipe Updated Succesfully"));
				}
				else
				{
					exit(CreateResponse("Failure", "You Don't Own This Recipe"));
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
	else if($request_method === "PUT")
	{

	}
	else if($request_method === "DELETE")
	{

	}
	else
	{
		exit(CreateResponse("Failure", "Bad Request Method"));
	}
?>