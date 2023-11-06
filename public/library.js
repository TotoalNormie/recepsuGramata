function GenericRequest(url, method, onLoadEnd, body=null, contentType="application/x-www-form-urlencoded")
{
	try
	{
		let request = new XMLHttpRequest();
		request.open(method, url);

		request.addEventListener("loadend", onLoadEnd);
		let requestBody = "";
		if(body !== null)
		{
			if(contentType != null)
			{
				request.setRequestHeader("Content-Type", contentType);

				if(contentType === "application/x-www-form-urlencoded")
				{
					for(const name in body)
						requestBody += encodeURIComponent(name) + "=" + encodeURIComponent(body[name]) + "&";

					requestBody = requestBody.substr(0, requestBody.length - 1);
				}
				else if(contentType === "application/json")
				{
					requestBody = JSON.stringify(body);
				}
				else
					throw "unsupported content type";
			}
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

function JoinMatches(matches)
{
	matches.forEach((v, i, arr)	=> arr[i] = v[0]);
	return matches.join("");
}

function NumInputValidator(element)
{
	if(element && element.tagName == "INPUT" && element.getAttribute("type") == "text")
	{
		element.addEventListener("input", function() // make sure its only integers
		{
			element.value = JoinMatches([...element.value.matchAll(/(^-)|(\d+)/g)]);
		})

		element.addEventListener("change", function() // clamp them
		{
			if(element.getAttribute("min") && element.value < +element.getAttribute("min"))
				element.value = +element.getAttribute("min");
			else if(element.getAttribute("max") && element.value > +element.getAttribute("max"))
				element.value = +element.getAttribute("max");
		})
	}
	else
		throw "Bad Element Passed To NumInputValidator";
}

function ParseJSON(responseText) // just suppresses errors
{
	let response = null;
	try
	{
		response = JSON.parse(responseText);
	}catch{}
	return response;
}

function GetCookie(name)
{
	let cookieString = decodeURIComponent(document.cookie);
	let searchStr = name + "=";
	let cookieStart = cookieString.indexOf(searchStr);
	if(cookieStart !== -1)
	{
		cookieStart =  + searchStr.length;
		let cookieEnd = cookieString.indexOf(";", cookieStart);

		return cookieString.substring(cookieStart, cookieEnd !== -1 ? cookieEnd + 1 : undefined);
	}
	else
		return false;
}