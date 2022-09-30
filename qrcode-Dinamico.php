<?php

require __DIR__.'/vendor/autoload.php';

use \App\Pix\Api;
use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;


$objectApiPix = new Api('https://oauth.sandbox.bb.com.br/oauth/token',
                        'ClientID',
                        'clienteSecret',
                        'diretorioCertificado');






$request = [
    "calendario" =>[
        "expiracao" => 3600
    ],
    "devedor" => [
        "cpf" => "12345678909",
        "nome" => "Francisco da silva"
    ],
    "valor" => [
        "original" => "10.00"
    ],
    "chave" => "12345678909",
    "solicitacaoPagador" => "Pagamento do curso TECNICO"
];  
echo "<pre>";
print_r($request);
echo "</pre>";



