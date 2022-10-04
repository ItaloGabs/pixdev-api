<?php

require __DIR__.'/vendor/autoload.php';

use \App\Pix\Api;
use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;


$objectApiPix = new Api('https://api.hm.bb.com.br/',
                        'ClientId',
                        'ClientSecretId');
                        






$request = [
    "calendario" =>[
        "expiracao" => 3600
    ],
    "devedor" => [
        "cpf" => "12234235568",
        "nome" => "Italo Gabriel dos Santos Tavares"
    ],
    "valor" => [
        "original" => "10.00"
    ],
    "chave" => "d14d32de-b3b9-4c31-9f89-8df2cec92c50",
    "solicitacaoPagador" => "Pagamento do curso TECNICO"
];  

$response = $objectApiPix->createCob('ITALO132523648494903038374', $request);
echo "<pre>";
print_r($response);
echo "</pre>";

if(!isset($response['location'])){
    echo "<h1>Problemas ao gerar Pix Dinamico</h1>";
    
}


// passando os dados do usuario
$objetctPayload = (new Payload) -> setMerchantName('ItaloGabriel')
                                -> setMerchantCity('MACEIO')
                                -> setAmount($response['valor']['original'])
                                -> setTxid($response['txid'])
                                -> setUrl($response['location'])
                                -> setUnicoPagamento(true);


// Codigo de pagamento pix
$payloadQrCode = $objetctPayload->getPayload();
    echo "<pre>";
    print_r($payloadQrCode);
    echo "</pre>";

// Cria o QRCODE
// $geraQrCode = new QrCode($response['textoImagemQRcode']);
$geraQrCode = new QrCode($payloadQrCode);

// IMG do Qrcode sendo gerado
$imgQrCode = (new Output\Png)->output($geraQrCode,400);

// header('Content-Type: image/png');
// echo $imgQrCode;

// echo "<pre>";
// print_r($payloadQrCode);
// echo "</pre>";

// echo "<pre>";
// print_r($objetctPayload);
// echo "</pre>";


?>

<h1>QR CODE DINAMICO PIX</h1>
<br>

<img src="data:image/png;base64,<?php echo base64_encode($imgQrCode)?>">

<br><br>

Código pix: <br>
<strong><?=$payloadQrCode?></strong>