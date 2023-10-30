if(!GetCookie("token"))
	window.location.href = "index.html";

let TitleInput = document.querySelector("input#title");
let ImageInput = document.querySelector("input#image_url");
let DescriptionInput = document.getElementById("description");

let IngredientContainer = document.querySelector("div#ingredient_container");

let IngredientTemplate = document.querySelector("template#ingredient_template");
let RemoveIngredientTemplate = document.querySelector("template#remove_ingredient_button");

let ingredientValueInputs = IngredientContainer.querySelectorAll("div.ingredient>input[name=\"value\"]"); // manually validate the existing input fields
for(let i = 0; i<ingredientValueInputs.length; ++i)
	NumInputValidator(ingredientValueInputs[i]); 

let RequestOutput = document.getElementById("request_output");

let MinIngredientCount = 1;
let IngredientCount = document.querySelectorAll("div#ingredient_container > .ingredient").length;

function IngredientRemover(ingredientToRemove)
{
	return function()
	{
		ingredientToRemove.remove();
		--IngredientCount;
		if(IngredientCount<=MinIngredientCount)
		{
			let ingredientNodes = document.querySelectorAll("div#ingredient_container > .ingredient");
			IngredientCount = ingredientNodes.length; // just to be safe
			for(let i = 0; i < MinIngredientCount; ++i)
			{
				let removeIngredientButton = ingredientNodes[i].querySelector(".remove_ingredient");
				if(removeIngredientButton !== null)
					removeIngredientButton.remove();
			}
		}
	}
}

document.querySelector("button#add_ingredient").addEventListener("click", function()
{
	let ingredient = IngredientContainer.appendChild(document.createElement("div"));
	ingredient.className = "ingredient";

	ingredient.innerHTML = IngredientTemplate.innerHTML;

	NumInputValidator(ingredient.querySelector("input[name=\"value\"]"));

	ingredient.querySelector("button.remove_ingredient").addEventListener("click", IngredientRemover(ingredient));

	++IngredientCount;
	if((IngredientCount-1) === MinIngredientCount) // we want to do this only once
	{
		let ingredientNodes = document.querySelectorAll("div#ingredient_container > .ingredient");
		IngredientCount = ingredientNodes.length; // just to be safe
		for(let i = 0; i < IngredientCount; ++i)
		{
			if(ingredientNodes[i].querySelector(".remove_ingredient") === null)
			{
				let newRemoveButton = ingredientNodes[i].appendChild(document.createElement("button"));
				newRemoveButton.outerHTML = RemoveIngredientTemplate.innerHTML;
				
				newRemoveButton = ingredientNodes[i].querySelector("button.remove_ingredient"); // so the person working on html can easily add and remove extra classes

				newRemoveButton.addEventListener("click", IngredientRemover(ingredientNodes[i]));
			}
		}
	}
});

document.querySelector("button#submit").addEventListener("click", function()
{
	if(TitleInput.value === "" || DescriptionInput.value === "" || ImageInput.value === "")
	{
		RequestOutput.innerText = "Please fill out all the fields";
		return;
	}
	
	let ingredientNames = IngredientContainer.querySelectorAll("div#ingredient_container .ingredient > input[name=\"name\"]");
	let ingredientValues = IngredientContainer.querySelectorAll("div#ingredient_container .ingredient > input[name=\"value\"]");
	let ingredientMeasurements = IngredientContainer.querySelectorAll("div#ingredient_container .ingredient > select[name=\"measurement\"]");
	
	let ingredients = [];
	for(let i = 0; i < ingredientNames.length; ++i)
	{
		if(ingredientNames[i].value === "" || ingredientValues[i].value === "" || ingredientMeasurements[i].value === "")
		{
			RequestOutput.innerText = "Please fill out all the fields";
			return;
		}

		ingredients[i] = {
			name: ingredientNames[i].value,
			value: ingredientValues[i].value,
			measurement: ingredientMeasurements[i].value
		};
	}
	
	GenericRequest("/recipes.php", "PUT", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					RequestOutput.innerText = response.message;
				}
			}
		}
	},
	{
		title: TitleInput.value,
		description: DescriptionInput.value,
		image_url: ImageInput.value,
		ingredient_json: JSON.stringify(ingredients)
	})
})