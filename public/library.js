function GenericRequest(url, method, onLoadEnd, body=null, contentType="application/x-www-form-urlencoded")
{
	try
	{
		let request = new XMLHttpRequest();
		request.open(method, url);
		if(contentType !== null)
			request.setRequestHeader("Content-Type", contentType);

		request.addEventListener("loadend", onLoadEnd);
		let requestBody = "";
		if(body !== null)
		{
			if(contentType === "application/x-www-form-urlencoded")
			{
				for(const name in body)
					requestBody += encodeURIComponent(name) + "=" + encodeURIComponent(body[name]) + "&";

				requestBody = requestBody.substr(0, requestBody.length - 1); // not sure if this is neccecary, cba to check, so here it is anyway
			}
			else if(contentType === "application/json")
			{
				requestBody = JSON.stringify(body);
			}
			else
				throw "only form urlencoded is supported rn";
		}

		request.send(requestBody);
		return true;
	}
	catch(error)
	{
		//console.log(error.message);
		return false;
	}
}

function ParseJSON(responseText)
{
	let response = null;
	try
	{
		response = JSON.parse(responseText);
	}catch{}
	return response;
}


if(false) // get all recipes
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
					console.log(response.data);

					// use the retrieved data here
				}
			}
		}
	});
}
else if(false) // get single recipe
{
	let targetRecipe = 2;
	GenericRequest("/recipes.php?id=" + targetRecipe, "GET", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					console.log(response.data);

					// use the retrieved data here
				}
			}
		}
	});
}
else if(false) // update recipe
{
	let updateObject = {
		id: 2,
		title: "new title"
		// var but viens vai vairak property, updatos tikai dotos (skaties API docs)
	};
	GenericRequest("/recipes.php", "POST", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					console.log(response.message);

					// handle shit here
				}
			}
		}
	},
	updateObject);
}
else if(false) // create recipe
{
	let newRecipeObject = {
		title: "test title",
		description: "test desc",
		image_url: "valid url",
		ingredient_json: "{valid json} (formats serverim nav svarigs)"
	};
	GenericRequest("/recipes.php", "PUT", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					console.log(response.message);

					// handle shit here
				}
			}
		}
	},
	newRecipeObject);
}
else if(false) // delete recipe
{
	let targetRecipe = 3;
	GenericRequest("/recipes.php", "DELETE", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					console.log(response.message);

					// handle shit here
				}
			}
		}
	},
	{
		id: targetRecipe
	});
}
else if(true) // is recipe owner
{
	let targetRecipe = 2;
	GenericRequest("/is_recipe_owner.php?recipe_id="+targetRecipe, "GET", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					console.log(response.message + ": " + response.data);

					// use the retrieved data here
				}
			}
		}
	});
}