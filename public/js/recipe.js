const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

const RecipeContainer = document.querySelector('.kaste1');
const template = document.getElementById('recipeTemplate');
const moreToLoveTemplate = document.getElementById('moreToLoveTemplate');
const sidebarContainer = document.querySelector('.love');

let recipe, MoreToLove;

let sidebarRecipeCount = 3;

let isRecepieDuplicated = false;

function DisplayRecipe()
{
	document.querySelectorAll(".kaste1 > *").forEach(function(v){v.remove()});
    const cr = template.content.cloneNode(true); // cloned recipe, shortened for conviniance

    cr.querySelector('img').src = recipe.image_url;
    cr.querySelector('img').alt = recipe.title + ' attēls';
    cr.querySelector('h2').textContent = recipe.title;
    cr.querySelector('span').textContent = recipe.views;
    cr.querySelector('p').textContent = recipe.description;

	let saveButton = cr.querySelector('button.Saglabat')
	if(recipe.bookmarked)
		saveButton.classList.add("saved");

	saveButton.addEventListener("click", function()
	{
		GenericRequest("/backend/bookmarks.php", saveButton.classList.toggle("saved") ? "PUT" : "DELETE", function()
		{
				
		},
		{
			recipe_id: recipe.ID
		});
	})

    const ingredients = JSON.parse(recipe.ingredient_json);

    // if()
    for(ing of ingredients) {
        const li = document.createElement('li');
        li.textContent = `${ing.name}: ${ing.value} ${ing.measurement}`;
        cr.querySelector('ul').appendChild(li);
    }

    RecipeContainer.appendChild(cr);
}

function UpdateRecipe()
{
    // console.log(id);
    if(!id) return;

	GenericRequest("../backend/recipes.php?id="+id, "GET", function()
	{
        // console.log(this.responseText);
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

function UpdateMoreToLove () {
    console.log("asdf");
    GenericRequest("../backend/recipes.php?limit="+sidebarRecipeCount+"&sort=views", "GET", function()
	{
        //console.log(this.responseText);
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
            //console.log(response);

			if(response)
			{
				if(response.status == "Success")
				{
					MoreToLove = response.data;
                    //console.log(MoreToLove);
					DispalyMoreToLove();
				}
			}
		}
	});
}

function DispalyMoreToLove() {
    document.querySelectorAll(".love > *").forEach(function(v){v.remove()});

	// loop of all selected reicpes
	for(let i = 0; i < MoreToLove.length; ++i) {
		let recipe = MoreToLove[i];
        if(recipe.ID === id && !isRecepieDuplicated) {
			sidebarRecipeCount++;
            isRecepieDuplicated = true;
            UpdateMoreToLove();
            return;
		}else if(recipe.ID === id) {
            continue;
        }
		const cr = moreToLoveTemplate.content.cloneNode(true); // cloned recipe, shortened for conviniance

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
			GenericRequest("/backend/bookmarks.php", saveButton.classList.toggle("saved") ? "PUT" : "DELETE", function()
			{
					
			},
			{
				recipe_id: recipe.ID
			});
		})

		sidebarContainer.appendChild(cr);
		
		//console.log(cr);

	}
}

function seeMore() {
    sidebarRecipeCount += 2;
    UpdateMoreToLove();
}

UpdateMoreToLove();