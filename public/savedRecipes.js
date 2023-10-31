if(!GetCookie("token"))
{
	alert("You need to be logged in to view saved recipes");
	window.location.href = "index.html";
}

let RecipeContainer = document.querySelector(".wrapper");
const recipeTemplate = document.getElementById('recepieTemplate');

let SearchBar = document.querySelector("#searchBar > input[type=\"text\"]");

let ViewSorting = {value: "DESC"}; // value: "DESC" | value: "none"

let RecipeList = [];

function DisplayRecipes()
{
	document.querySelectorAll(".wrapper > *").forEach(function(v){v.remove()});


	// loop of all selected reicpes
	for(recipe of RecipeList) {
		if(recipe.hidden)
			continue;

		const cr = recipeTemplate.content.cloneNode(true); // cloned recipe, shortened for conviniance

		cr.querySelector('a').href = 'recipe.html?id=' + recipe.ID;
		cr.querySelector('img').src = recipe.image_url;
		cr.querySelector('img').alt = recipe.title + ' attēls';
		cr.querySelector('h2').textContent = recipe.title;
		cr.querySelector('span').textContent = recipe.views;

		RecipeContainer.appendChild(cr);

	}
	// for(let i = 0; i < RecipeList.length; ++i)
	// {
	// 	let recipeElement = RecipeContainer.appendChild(document.createElement("div"));
	// 	recipeElement.className = "item";


	// 	let recipeImage = recipeElement.appendChild(document.createElement("img"));
	// 	console.log(recipeImage.src = RecipeList[i].image_url);		

	// 	let recipeTitle = recipeElement.appendChild(document.createElement("p1"));
	// 	recipeTitle.innerText = RecipeList[i].title;
	// }
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
	GenericRequest("../backend/bookmarks.php", "GET", function()
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