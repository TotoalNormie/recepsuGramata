let RecipeContainer = document.querySelector(".wrapper");
const recipeTemplate = document.getElementById('recepieTemplate');

let SearchBar = document.querySelector("#searchBar > input[type=\"text\"]");

let ViewSorting = {value: "DESC"}; // value: "DESC" | value: "none"

let RecipeList = [];

function DisplayRecipes()
{
	document.querySelectorAll(".wrapper > *").forEach(function(v){v.remove()});


	// loop of all selected reicpes
	for(let i = 0; i < RecipeList.length; ++i) {
		let recipe = RecipeList[i];

		if(recipe.hidden)
			continue;

		const cr = recipeTemplate.content.cloneNode(true); // cloned recipe, shortened for conviniance

		cr.querySelector('a').href = 'recipe.html?id=' + recipe.ID;
		cr.querySelector('img').src = recipe.image_url;
		cr.querySelector('img').alt = recipe.title + ' attēls';
		cr.querySelector('h2').textContent = recipe.title;
		cr.querySelector('span').textContent = recipe.views;

		let saveButton = cr.querySelector('button.Saglabat')
		if(recipe.bookmarked)
			saveButton.classList.add("saved");

		saveButton.addEventListener("click", function()
		{
			GenericRequest("../backend/bookmarks.php", saveButton.classList.toggle("saved") ? "PUT" : "DELETE", function()
			{
				
			},
			{
				recipe_id: recipe.ID
			});
		})

		RecipeContainer.appendChild(cr);

	}
}

function SortRecipes()
{
	if(ViewSorting.value !== "none")
	{
		if(ViewSorting.value === "ASC")
		{
			RecipeList.sort(function(a, b)
			{			
				if(a.views > b.views)
					return 1;
				else if(a.views < b.views)
					return -1;
				else
					return 0;
			});
		}
		else
		{
			RecipeList.sort(function(a, b)
			{			
				if(a.views < b.views)
					return 1;
				else if(a.views > b.views)
					return -1;
				else
					return 0;
			});
		}
	}
}

function FilterAndLoad()
{
	let searchValue = SearchBar.value;	

	for(let i = 0; i<RecipeList.length; ++i)
	{
		let thisRecipe = RecipeList[i]; // indexed array lookups are expensive

		if(searchValue !== "" && !thisRecipe.title.toLowerCase().includes(searchValue.toLowerCase()))
		{
			RecipeList[i].hidden = true;
		}
		else
		{
			RecipeList[i].hidden = false;
		}
	}
	
	DisplayRecipes();
}

SearchBar.addEventListener("input", FilterAndLoad);
//ViewSorting.addEventListener("change", SortRecipes)

function UpdateRecipes()
{
	GenericRequest("../backend/recipes.php", "GET", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					RecipeList = response.data;
					
					SortRecipes();
					FilterAndLoad();
				}
			}else {
				console.error(this.responseText);
			}
		}
	});
}

UpdateRecipes();