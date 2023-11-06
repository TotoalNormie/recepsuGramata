let LoginForm = document.querySelector("div#login_form");
let UserInput = document.querySelector("#user_input");
let PassInput = document.querySelector("#pass_input");

let RequestOutput = document.querySelector("#request_output");

LoginForm.querySelector("button").addEventListener("click", function()
{
	if(UserInput.value === "" || PassInput.value === "")
	{
		RequestOutput.innerText = "Please Fill Out All The Fields";
		return;
	}

	GenericRequest("../backend/login.php", "POST", function()
	{
		if(this.responseText)
		{
			let response = ParseJSON(this.responseText);
			if(response)
			{
				if(response.status == "Success")
				{
					window.location.href = "index.html";
				}
				else
				{
					RequestOutput.innerText = response.message;
				}
			}
			else
			{
				RequestOutput.innerText = "Something Went Wrong";
			}
		}
	},
	{
		user: UserInput.value,
		pass: PassInput.value
	})
});