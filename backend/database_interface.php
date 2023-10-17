<?php
	require_once("session_library.php");

	class RecipeDatabase
	{
		public $DB;

		public function __construct()
		{
			$this->DB = new mysqli("localhost", "root", "", "recipes");
		}

		public function GetConnectionError()
		{
			return $this->DB->connect_error;
		}

		public function CreateRecipe($title, $description, $image_url, $ingredient_json, $owner_user_identifier)
		{
			$stmt = $this->DB->prepare("INSERT INTO recipes(title, description, image_url, ingredient_json, owner) VALUES(?, ?, ?, ?, ?)");
			
			$owner = $this->GetUserDBID($owner_user_identifier);
			$stmt->bind_param("ssssi", $title, $description, $image_url, $ingredient_json, $owner);
			$stmt->execute();
		}

		public function ListRecipes()
		{
			$result = $this->DB->query("SELECT ID, title, image_url, views FROM recipes");
			if($result->num_rows<=0)
				return false;

			return $result->fetch_all(MYSQLI_ASSOC);
		}

		public function GetRecipeByID($id)
		{
			$stmt = $this->DB->prepare("SELECT title, description, image_url, views, ingredient_json FROM recipes WHERE ID=? LIMIT 1");
			
			$stmt->bind_param("i", $id);
			$stmt->execute();

			$result = $stmt->get_result();
			if($result->num_rows<=0)
				return false;

			return $result->fetch_array(MYSQLI_ASSOC);
		}

		public function DeleteRecipeByID($id)
		{
			$stmt = $this->DB->prepare("DELETE FROM recipes WHERE ID=?");
			
			$stmt->bind_param("i", $id);
			$stmt->execute();
		}

		public function UpdateRecipeByID($id, $title, $description, $image_url, $ingredient_json)
		{
			$stmt = $this->DB->prepare("UPDATE recipes SET title=COALESCE(?, title), description=?, image_url=?, ingredient_json=? WHERE ID=? LIMIT 1");
			
			$stmt->bind_param("ssssi", $title, $description, $image_url, $ingredient_json, $id);
			$stmt->execute();
		}

		public function IncrementViewsByID($id)
		{
			$stmt = $this->DB->prepare("UPDATE recipes SET views = views + 1 WHERE ID=? LIMIT 1");
			
			$stmt->bind_param("i", $id);
			$stmt->execute();
		}

		public function IsRecipeOwner($recipe_id, $user_identifier)
		{
			$stmt = $this->DB->prepare("SELECT users.identifier FROM recipes WHERE recipes.ID=?
			                            INNER JOIN users ON recipes.owner = users.ID
			                            LIMIT 1");
			
			$stmt->bind_param("i", $recipe_id);
			$stmt->execute();

			$result = $stmt->get_result();
			if($result->num_rows<=0)
				return false;

			return $result->fetch_array(MYSQLI_NUM)[0] == $user_identifier;
		}

		public function RegisterUser($username, $password)
		{
			$stmt = $this->DB->prepare("INSERT INTO users(identifier, username, passhash) VALUES(?, ?, ?)");
			
			// these have to be references
			$passhash = password_hash($password,  PASSWORD_BCRYPT);
			$identifier = GenerateUserID($username, $passhash); 

			$stmt->bind_param("sss", $identifier, $username, $passhash);
			$stmt->execute();
		}

		public function UserExists($username)
		{
			$stmt = $this->DB->prepare("SELECT ID FROM users WHERE username=?");
			
			$stmt->bind_param("s", $username);
			$stmt->execute();

			$result = $stmt->get_result();
			if($result->num_rows<=0)
				return false;
			else
				return true;
		}

		public function GetUserIdentifier($username, $password)
		{
			$stmt = $this->DB->prepare("SELECT identifier FROM users WHERE username=? AND passhash=?");
			
			$passhash = password_hash($password,  PASSWORD_BCRYPT);
			$stmt->bind_param("ss", $username, $passhash);
			$stmt->execute();

			$result = $stmt->get_result();
			if($result->num_rows<=0)
				return false;

			return $result->fetch_array(MYSQLI_NUM)[0];
		}		

		public function GetUserDBID($user_identifier)
		{
			$stmt = $this->DB->prepare("SELECT ID FROM users WHERE identifier=?");
			
			$stmt->bind_param("s", $user_identifier);
			$stmt->execute();

			$result = $stmt->get_result();
			if($result->num_rows<=0)
				return false;

			return $result->fetch_array(MYSQLI_NUM)[0];
		}
	}
?>