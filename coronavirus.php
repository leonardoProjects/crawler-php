<?php

include 'Curl.php';

function formatDate($date): string
{
$date = str_replace('-', '/', $date);
$dateValid = explode('T', $date);
$date = array_reverse(explode('/', $dateValid[0]));
$hours = $dateValid[1];
$date = 'Última atualização: '.implode('/', $date).', às '.substr($hours, 0, 5);

return $date;
}

$resp = new Curl();
$url = 'https://www.saude.gov.br/noticias';
$resp -> url = $url;
$resp ->method = 'GET';
$resp ->Request();

$string = $resp->getStr($resp->data, 'ATUALIZAÇÃO DOS CASOS', '</a>');
$parteUrl  = $resp->getStr($string, 'href="', '"');

$urlNoticia = $url.''.$parteUrl;
$resp -> url = $urlNoticia;
$resp ->method = 'GET';
$resp ->Request();

$result = $resp->getStr($resp->data, '<script type="application/ld+json">', '</');
$json = json_decode($result, true);

$response = [];
$response['headline'] = $json['headline'];
$date = formatDate($json['datePublished']);
$response['datePublished'] = $date;
$response['name'] = $json['publisher']['name'];
$response['description'] = $json['description'];

$response = json_encode($response, true);
echo $response;

?>