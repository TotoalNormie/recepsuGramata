<?php
	function CreateResponse($status, $message, $optionalName="", $optionalMessage="")
	{
		$array = [
			"status" => $status,
			"message" => $message
		];

		if($optionalName !== "" && $optionalMessage !== "")
			$array[$optionalName] = $optionalMessage;

		return json_encode($array);
	}

	function IsValidDate($dateString)
	{
		if(gettype($dateString) == "string")
		{
			if(strlen($dateString) == 10)
			{
				$year = intval(substr($dateString, 0, 4));
				$month = intval(substr($dateString, 5, 2));
				$day = intval(substr($dateString, 8, 2)); 

				return  $year >= 1970 && ($month >= 1 && $month <= 12) && ($day >= 1 && $day <= 31);
			}
			else
				return false;
		}
			
		return false;
	}

	function GetAllRequestData()
	{
		$out = '';
		$out = file_get_contents("php://input");

		$decodedJson = json_decode($out, true);
		if($decodedJson === null)
			mb_parse_str(file_get_contents("php://input"), $out);
		else
			$out = $decodedJson;

		foreach($_GET as $key => $value) // more code to get around the retarded restrictions of web standards
		{
			$out[$key] = $value;
		}

		return $out;
	}
?>