<?php

require __DIR__.'/vendor/autoload.php';

use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

// passando os dados do usuario
$objetctPayload = (new Payload) -> setPixKey("chavepix")
                                -> setDescription('Pagamento do curso TECNICO INFORMATICA')
                                -> setMerchantName('ItaloGabriel')
                                -> setMerchantCity('MACEIO')
                                -> setAmount(10.00)
                                -> setTxid('ITARUDEV');


// Codigo de pagamento pix
$payloadQrCode = $objetctPayload->getPayload();


// Cria o QRCODE
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

<h1>QR CODE ESTATICO PIX</h1>
<br>

<img src="data:image/png;base64,<?php echo base64_encode($imgQrCode)?>">

<br><br>

Código pix: <br>
<strong><?=$payloadQrCode?></strong>