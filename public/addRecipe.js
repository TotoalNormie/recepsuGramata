let TitleInput = document.querySelector("input#title");
let ImageInput = document.querySelector("input#image_url");
let DescriptionInput = document.getElementById("description");

let IngredientContainer = document.querySelector("div#ingredient_container");
let IngredientTemplate = document.querySelector("template#ingredient_template");

let ingredientValueInputs = IngredientContainer.querySelectorAll("div.ingredient>input[name=\"value\"]"); // manually validate the existing input fields
for(let i = 0; i<ingredientValueInputs.length; ++i)
	NumInputValidator(ingredientValueInputs[i]); 

let RequestOutput = document.getElementById("request_output");

document.querySelector("button#add_ingredient").addEventListener("click", function(event)
{
	event.preventDefault();
	let ingredient = IngredientContainer.appendChild(document.createElement("div"));
	ingredient.className = "ingredient";

	ingredient.innerHTML = IngredientTemplate.innerHTML;

	NumInputValidator(ingredient.querySelector("input[name=\"value\"]"));

	ingredient.querySelector("button.remove_ingredient").addEventListener("click", () => ingredient.remove());
});

document.querySelector("button#submit").addEventListener("click", function()
{
	if(TitleInput.value === "" || DescriptionInput.value === "" || ImageInput.value === "")
	{
		RequestOutput.innerText = "Please fill out all the fields";
		return;
	}
	
	let ingredientNames = IngredientContainer.querySelectorAll(".ingredient > input[name=\"name\"]");
	let ingredientValues = IngredientContainer.querySelectorAll(".ingredient > input[name=\"value\"]");
	let ingredientMeasurements = IngredientContainer.querySelectorAll(".ingredient > select[name=\"measurement\"]");
	
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