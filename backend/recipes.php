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
					if($user_session)
						$recipe["bookmarked"] = $DB->IsBookmarked($recipe["ID"], $user_session->user_id);

					$DB->IncrementViewsByID($GetData["id"]);
					++$recipe["views"];
					
					exit(CreateResponse("Success", "Recipe Retrieved Succesfully", "data", $recipe));
				}
				else
				{
					exit(CreateResponse("Failure", "Recipe Not Found"));
				}
			}
			else
			{
				$recipes = null;
				if(isset($GetData["limit"]))
					$recipes = $DB->ListRecipesWithLimit($GetData["limit"], $GetData["sort"] ?? "");
				else
					$recipes = $DB->ListRecipes();

				if($user_session)
				{
					$bookmarks = $DB->GetBookmarkedRecipes($user_session->user_id);

					$bookmarkCount = count($bookmarks);
					$recipeIDList = array_column($recipes, "ID");
					
					for($i = 0; $i < $bookmarkCount; ++$i)
					{
						$index = array_search($bookmarks[$i]["ID"], $recipeIDList);
						if($index !== false)
							$recipes[$index]["bookmarked"] = true;
					}
				}
				exit(CreateResponse("Success", "Recipes Retrieved Succesfully", "data", $recipes));
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

			$PostData = GetAllRequestData();
			if(isset($PostData["id"]) && (isset($PostData["title"]) || isset($PostData["description"]) || isset($PostData["image_url"]) || isset($PostData["ingredient_json"])))
			{
				$ownership = $DB->IsRecipeOwner($PostData["id"], $user_session->user_id);
				if($ownership)
				{
					$DB->UpdateRecipeByID($PostData["id"], $PostData["title"] ?? null, $PostData["description"] ?? null, $PostData["image_url"] ?? null, $PostData["ingredient_json"] ?? null);
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
					if($DB->DeleteRecipeByID($DeleteData["id"]))
						exit(CreateResponse("Success", "Recipe Deleted Succesfully"));
					else
						exit(CreateResponse("Failure", "Something Failed While Deleting Recipe"));
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