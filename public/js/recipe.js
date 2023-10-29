const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

const RecipeContainer = document.querySelector('.kaste1');
const template = document.getElementById('recipeTemplate');

let recipe;

function DisplayRecipe()
{
	document.querySelectorAll(".kaste1 > *").forEach(function(v){v.remove()});
    console.log(recipe);
    const cr = template.content.cloneNode(true); // cloned recipe, shortened for conviniance

    cr.querySelector('img').src = recipe.image_url;
    cr.querySelector('img').alt = recipe.title + ' attēls';
    cr.querySelector('h2').textContent = recipe.title;
    cr.querySelector('span').textContent = recipe.views;
    cr.querySelector('p').textContent = recipe.description;

    const ingredients = JSON.parse(recipe.ingredient_json);

    // if()
    for(ing of ingredients) {
        const li = document.createElement('li');
        li.textContent = `${ing.name}: ${ing.value} ${ing.mesurment}`;
        cr.querySelector('ul').appendChild(li);
    }
    console.log(ingredients);


    RecipeContainer.appendChild(cr);
    
    console.log(cr);
}

function UpdateRecipe()
{
    if(!id) return;

	GenericRequest("../backend/recipes.php?id="+id, "GET", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					recipe = response.data;

					DisplayRecipe();
				}
			}
		}
	});
}

UpdateRecipe();