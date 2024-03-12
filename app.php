<?php

define('API_BASE', 'http://localhost/api_e_app/api/index.php?option=');

echo '<p>APLICAÇÃO</p>';

for($i=0; $i<10; $i++)
{
    $resultado = api_request('random&min=100&max=200');

    // verify is response is ok (success)
    if($resultado['status'] == 'ERROR'){
        die('Aconteceu um erro na minha chamada à API.');
    }
   
    echo "O valor randômico:" . $resultado['data'] . "<br>";

}

echo 'TERMINADO';


function api_request($option)
{
    $client = curl_init(API_BASE . $option);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($client);
    return json_decode($response, true);
}