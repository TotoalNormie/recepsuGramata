<?php
	require_once("session_library.php");
	$user_session = HandleSessionSoft();

	require_once("database_interface.php");
	require_once("utility.php");

	$request_method = $_SERVER["REQUEST_METHOD"];
	$DB = new RecipeDatabase();

	if($request_method === "GET")
	{
		try
		{
			$GetData = GetAllRequestData();
			
			if(isset($GetData["id"]))
			{
				$recipe = $DB->GetRecipeByID($GetData["id"]);
				if($recipe)
				{
					$DB->IncrementViewsByID($GetData["id"]);
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
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->getMessage()));
		}
	}
	else if($request_method === "POST") // update recipe
	{
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));

			if(isset($_POST["id"]) && (isset($_POST["title"]) || isset($_POST["description"]) || isset($_POST["image_url"]) || isset($_POST["ingredient_json"])))
			{
				$ownership = $DB->IsRecipeOwner($_POST["id"], $user_session->user_id);
				if($ownership)
				{
					$DB->UpdateRecipeByID($_POST["id"], $_POST["title"] ?? null, $_POST["description"] ?? null, $_POST["image_url"] ?? null, $_POST["ingredient_json"] ?? null);
					exit(CreateResponse("Success", "Recipe Updated Succesfully"));
				}
				else if($ownership === null)
				{
					exit(CreateResponse("Failure", "Recipe Not Found"));
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
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->getMessage()));
		}
	}
	else if($request_method === "PUT") // create recipe
	{
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));
			

			$PutData = GetAllRequestData();

			if(isset($PutData["title"]) && isset($PutData["description"]) && isset($PutData["image_url"]) && isset($PutData["ingredient_json"]))
			{
				$DB->CreateRecipe($PutData["title"], $PutData["description"], $PutData["image_url"], $PutData["ingredient_json"], $user_session->user_id);
				exit(CreateResponse("Success", "Recipe Created Succesfully"));
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
	else if($request_method === "DELETE") // delete recipe
	{		
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));

			$DeleteData = GetAllRequestData();
			if(isset($DeleteData["id"]))
			{
				$ownership = $DB->IsRecipeOwner($DeleteData["id"], $user_session->user_id);
				if($ownership)
				{
					$DB->DeleteRecipeByID($DeleteData["id"]);
					exit(CreateResponse("Success", "Recipe Deleted Succesfully"));
				}
				else if($ownership === null)
				{
					exit(CreateResponse("Failure", "Recipe Not Found"));
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
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->getMessage()));
		}
	}
	else
	{
		exit(CreateResponse("Failure", "Bad Request Method"));
	}
?>