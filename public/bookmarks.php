<?php
	require_once("session_library.php");
	$user_session = HandleSessionSoft();

	require_once("database_interface.php");
	require_once("utility.php");

	$request_method = $_SERVER["REQUEST_METHOD"];
	$DB = new RecipeDatabase();

	if($request_method === "GET") // get bookmarks
	{
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));
			
			exit(CreateResponse("Success", "Recipes Retrieved Succesfully", "data", $DB->GetBookmarkedRecipes($user_session->user_id)));
		}
		catch(Exception $e)
		{
			exit(CreateResponse("Failure", "Something Went Wrong - Server Side Error", "error", $e->getMessage()));
		}
	}
	else if($request_method === "PUT") // set bookmark
	{
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));			

			$PutData = GetAllRequestData();

			if(isset($PutData["recipe_id"]))
			{
				if($DB->GetRecipeByID($PutData["recipe_id"]) === null)
					exit(CreateResponse("Failure", "Recipe Not Found"));
				
				if($DB->IsBookmarked($PutData["recipe_id"], $user_session->user_id))
					exit(CreateResponse("Failure", "Recipe Already Bookmarked"));

				$DB->SetBookmark($PutData["recipe_id"], $user_session->user_id);
				exit(CreateResponse("Success", "Recipe Bookmarked Succesfully"));
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
	else if($request_method === "DELETE") // remove bookmark
	{		
		try
		{
			if($user_session === false)
				exit(CreateResponse("Failure", "Please Authenticate Yourself"));

			$DeleteData = GetAllRequestData();

			if(isset($DeleteData["recipe_id"]))
			{
				if($DB->RemoveBookmark($DeleteData["recipe_id"], $user_session->user_id))
					exit(CreateResponse("Success", "Bookmarked Removed Succesfully"));
				else
					exit(CreateResponse("Failure", "Bookmark Not Found"));
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