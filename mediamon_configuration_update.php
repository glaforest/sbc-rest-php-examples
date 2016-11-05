<?php
/*
 * mediamon configuration update 
*/
include_once 'setting.inc.php';      
require_once PEST_PATH.'pest/PestJSON.php';

$options['server'] = 'http://' . SERVER;
$options['request'] = 'POST';

$base_url = $options['server'] .'/SAFe/sng_rest/api/';
$method_name = 'update';
$module_name = 'mediamon';
$obj_type = 'configuration';
$obj_name = '';

$request_uri =  $method_name .'/'. $module_name .'/'. $obj_type .'/'. $obj_name;

//be care fule rest api enable if set to false, all rest api can  not access. 
$json_data ='
{
		"global\/NSC_MEDIAMON_ENABLE": "true",
		"mediamon\/dynamic-rules": "false",
		"logging\/level": "info",
		"logging\/syslog": "true"
	}';
$data = json_decode($json_data);
try {
    //API_KEY defined in 'setting.inc.php' send api key in header
    //$header_array[]="X-API-KEY:C2FF69E5B86551C8062F9B56E1065370"
    //$header_array[]="Content-Type: application/json" auto set by PestJSON
    if(defined('API_KEY'))
    {
        $header_array[] = 'X-API-KEY:'.API_KEY;
    }else{
        $header_array = null;
    }
    $pest = new PestJSON($base_url);
    
    $status = $pest->post($request_uri, $data, $header_array);
} catch(Exception $e) {
    $status = json_decode($e->getMessage(), true);
}

$rc = $pest->lastStatus();
print('URL      : ' . $base_url.$request_uri.PHP_EOL);
print('Output   : ' . json_encode($status) .PHP_EOL);
print('Status   : ' . $rc.PHP_EOL);

/*
 * Output sample
{
	"status": true,
	"method": "update",
	"module": "mediamon",
	"type": "configuration"
}Status: 200

{
	"status": false,
	"error": {
		"global\/NSC_MEDIAMON_ENABLE": "Read Only."
	},
	"method": "update",
	"module": "mediamon",
	"type": "configuration"
}Status: 400
 */
?>