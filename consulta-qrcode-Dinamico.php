<?php

require __DIR__.'/vendor/autoload.php';

use \App\Pix\Api;
use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;


$objectApiPix = new Api('https://api.hm.bb.com.br/',
                        'ClientId',
                        'ClientSecretId');
                        



$response = $objectApiPix->consultCob('ITALO132523648494903038374');

if(!isset($response['location'])){
    echo "<h1>Problemas ao consultar gerar Pix Dinamico</h1>";
    echo "<pre>";
    print_r($response);
    echo "</pre>";
}
echo "<pre>";
print_r($response);
echo "</pre>";
