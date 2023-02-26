<?php

	/** Variable Naming convention
		Scope			:	- g (Global)
								- l	(Local)
								- t (Function Parameter)
								- p (Property Class)
		DataTipe	: -	c (char, varchar, string, etc)
								- n (numeric, int, etc)
								- o (Object)
		example		: -	gcxxxx (global char)
								- gnxxxx (global numeric)
								- goxxxx (global object)
	**/

	//------------------- Init Global Variable -------------------//
	/**
		Sequence initialize object are important !!
	 **/

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");			

	require_once ('inc/global.php');
	$goAppConfig 				= new GlobalAppConfig();

	require_once ('inc/exceptionhandler.php');
	$goExceptionHandler	= new ExceptionHandler();

	require_once ('inc/logapi.php');
	$goLogApi						= new LogApi();

	require_once ("inc/restapi.php");
	$goRestApi					= new RestApi();

	require_once ("inc/connectiondb.php");
	$goConnectionDbPasnet			= new ConnectionDb("mfast");

	require_once ("inc/Helpers.php");
	$goHelpers = new Helpers();

	require_once ("inc/FileWriter.php");
	$goFileWriter = new FileWriter();
	
	//------------------- Init Global Variable -------------------//

	$function = isset($_GET['f']) ? $_GET['f'] : "";
	$data = json_decode(file_get_contents('php://input'));

	/** Get URL parameters **/
	$url = $_SERVER['REQUEST_URI'];
	$url_params = (parse_url($url, PHP_URL_QUERY));	

	if ($function != "") {
		try {

			$arryFunction = explode("/",$function);
			$cnt = count($arryFunction);

			$folder = "";
			if ($cnt == 1) {
				require_once ($GLOBALS["goAppConfig"]->appBasePath . "/controller.php");
				$controller = new Controller();
			} elseif ($cnt > 1) {
				for($i=1;$i<=$cnt-1;$i++) {
					$folder .= "/".$arryFunction[$i-1];
				}
				require_once ($GLOBALS["goAppConfig"]->appBasePath . $folder . "/controller.php");
				$controller = new Controller();
				$function = $arryFunction[$cnt-1];	
			}
			
			/** Call function inside controller.
					Pass in json body and/or URL query string.
			**/
			$fcntrl = $function."_action";
			
			if (!empty($data) && !empty($url_params)) {
				$controller->$fcntrl($data, $url_params);
			} elseif (!empty($data) && empty($url_params)) {
				$controller->$fcntrl($data);
			} elseif (empty($data) && !empty($url_params)) {
				$controller->$fcntrl(NULL, $url_params);
			} else {
				$controller->$fcntrl();
			}
		} catch (Throwable $e) {
			$r["status_code"] = "97";
			$r["status_desc"] = "ERROR - Not Found";
			$r["message"] = $e->getMessage();
			echo json_encode($r);			
		}
	} else {
		$r["status_code"] = "97";
		$r["status_desc"] = "ERROR - Not Found";				
		$r["message"] = "Function not found.";
		echo json_encode($r);
	}

?>