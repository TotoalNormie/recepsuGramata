let RecipeContainer = document.querySelector(".wrapper");
const template = document.getElementById('recepieTemplate');

let RecipeList = [];


function DisplayRecipes()
{
	document.querySelectorAll(".wrapper > *").forEach(function(v){v.remove()});


	// loop of all selected reicpes
	for(recipe of RecipeList) {
		console.log(recipe);
		const cr = template.content.cloneNode(true); // cloned recipe, shortened for conviniance

		cr.querySelector('a').href = 'recipe.html?id=' + recipe.ID;
		cr.querySelector('img').src = recipe.image_url;
		cr.querySelector('img').alt = recipe.title + ' attēls';
		cr.querySelector('h2').textContent = recipe.title;
		cr.querySelector('span').textContent = recipe.views;

		RecipeContainer.appendChild(cr);
		
		console.log(cr);

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

//DisplayRecipes();

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

					DisplayRecipes();
				}
			}
		}
	});
}

UpdateRecipes();