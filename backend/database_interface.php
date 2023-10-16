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

		public function ListRecipes()
		{
			$result = $this->DB->query("SELECT ID, title, image_url FROM recipes");
			if($result->num_rows<=0)
				return false;

			return $result->fetch_all(MYSQLI_ASSOC);
		}

		public function GetRecipeByID($id)
		{

		}

		public function DeleteRecipeByID($id)
		{
			$stmt = $this->DB->prepare("DELETE FROM ingredients WHERE recipe_id=?");
			
			$stmt->bind_param("i", $id);
			$stmt->execute();


			$stmt = $this->DB->prepare("DELETE FROM recipes WHERE ID=?");
			
			$stmt->bind_param("i", $id);
			$stmt->execute();
		}

		public function UpdateRecipeByID($id, $title, $description, $image_url, $recipe_json)
		{
			
		}
		public function IsRecipeOwner($recipe_id, $user_identifier)
		{

		}

		public function RegisterUser($username, $password)
		{
			$stmt = $this->DB->prepare("INSERT INTO users(identifier, username, passhash) VALUES(?, ?, ?)");
			
			// these have to be references
			$identifier = GenerateUserID($username, $password); 
			$passhash = password_hash($password,  PASSWORD_BCRYPT);

			$stmt->bind_param("sss", $identifier, $username, $passhash);
			$stmt->execute();
		}

		public function UserExists($username)
		{
			
		}
	}
?>