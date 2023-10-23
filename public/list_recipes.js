let RecipeContainer = document.getElementById("recipe_container");

let RecipeList = [];

function DisplayRecipes()
{
	document.querySelectorAll("#recipe_container > *").forEach(function(v){v.remove()});

	for(let i = 0; i < RecipeList.length; ++i)
	{
		let recipeElement = RecipeContainer.appendChild(document.createElement("div"));
		recipeElement.className = "item";

		let recipeImage = recipeElement.appendChild(document.createElement("img"));
		console.log(recipeImage.src = RecipeList[i].image_url);		

		let recipeTitle = recipeElement.appendChild(document.createElement("p1"));
		recipeTitle.innerText = RecipeList[i].title;
	}
}

//DisplayRecipes();

function UpdateRecipes()
{
	GenericRequest("/recipes.php", "GET", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					RecipeList = response.data;

					DisplayRecipes();
				}
			}
		}
	});
}

UpdateRecipes();