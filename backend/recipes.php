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
					$DB->UpdateRecipeByID($_REQUEST["id"], $_REQUEST["title"] ?? null, $_REQUEST["description"] ?? null, $_REQUEST["image_url"] ?? null, $_REQUEST["ingredient_json"] ?? null);
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
	else if($request_method === "PUT") // create recipe
	{
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));
			

			$PutData = GetPUT();

			if(isset($PutData["id"]) && isset($PutData["title"]) && isset($PutData["description"]) && isset($PutData["image_url"]) && isset($PutData["ingredient_json"]))
			{
				$DB->CreateRecipe($PutData["id"], $PutData["title"], $PutData["description"], $PutData["image_url"], $PutData["ingredient_json"], $user_session->user_id);
				exit(CreateResponse("Success", "Recipe Created Succesfully"));
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
	else if($request_method === "DELETE") // delete recipe
	{		
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));

			if(isset($_REQUEST["id"]))
			{
				if($DB->IsRecipeOwner($_REQUEST["id"], $user_session->user_id))
				{
					$DB->DeleteRecipeByID($_REQUEST["id"]);
					exit(CreateResponse("Success", "Recipe Deleted Succesfully"));
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
	else
	{
		exit(CreateResponse("Failure", "Bad Request Method"));
	}
?>