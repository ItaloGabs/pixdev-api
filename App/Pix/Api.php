<?php
namespace App\Pix;
/**
* 
* URL base do Provedor de Serviços de Pagamento(PSP): $baseUrl
* Client ID do oAuth2 do PSP: $clientId
* Client secret ID do oAuth2 do PSP: $clientSecret
* Caminho absoluto até o arquivo do certificado: $certificate
*
* @var string
*/

class Api{
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    private $certificate;

    public function __construct($baseUrl,$clientId,$clientSecret,$certificate){
        $this->baseUrl      = $baseUrl;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->certificate  = $certificate;
    }























}







?>