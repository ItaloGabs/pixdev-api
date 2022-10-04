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
* @var string
* @var string
* @var string
*/

class Api{
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    private $gw_dev_app_key;

    public function __construct($baseUrl,$clientId,$clientSecret,$gw_dev_app_key = 'developer_key'){
        $this->baseUrl         = $baseUrl;
        $this->clientId        = $clientId;
        $this->clientSecret    = $clientSecret;
        $this->gw_dev_app_key  = $gw_dev_app_key;
    }

    /**
    * Metodo responsavel por criar uma cobrança imediata
    * @var string $txid
    * @var array $request
    * @return array
    */

    public function createCob($txid,$request){
        return $this->send('PUT','pix/v1/cob/'.$txid.'?gw-dev-app-key='.$this->gw_dev_app_key, $request);

    }

    /**
    * Metodo responsavel por criar uma cobrança imediata
    * @var string $txid
    * @var array $request
    * @return array
    */

    public function consultCob($txid){
        return $this->send('GET','pix/v1/cob/'.$txid.'?gw-dev-app-key='.$this->gw_dev_app_key);

    }

    /**
    * Metodo responsavel por obter o token de acesso as APIs Pix
    * @return string
    */
    private function getAccessToken(){
        $endpoint = 'https://oauth.hm.bb.com.br/oauth/token';
        $gw_dev_key = $this->gw_dev_app_key;
        $finalEndpoint = $endpoint.'?gw-dev-app-key='.$gw_dev_key;

        print_r($finalEndpoint);
        
        $headers = [
            'Authorization: Codigo Basic',

            'Content-Type: application/x-www-form-urlencoded'
        ];

        $request = 'grant_type=client_credentials&scope=cob.read%20cob.write%20pix.read%20pix.write';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $finalEndpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $request,
            CURLOPT_HTTPHEADER     => $headers
          ]);

        
        
        $response = curl_exec($curl);
        curl_close($curl);
        
      
        $responseArray = json_decode($response,true);
        echo "<pre>";
        print_r($responseArray);
        echo "</pre>";
        
        return $responseArray['access_token'] ?? '';
        
    }

    /**
    * Metodo responsavel por enviar requisições para o PSP
    * @var string $metodo
    * @var string $recurso
    * @var array $request
    * @return array
    */
    private function send($metodo,$recurso,$request = []){
        // ENDPOINT COMPLETO
        $endpoint = $this->baseUrl.$recurso;
        
        echo($endpoint);

        // CABEÇALHO
        $headers = [
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '. $this->getAccessToken()
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL             => $endpoint,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => $metodo,
            CURLOPT_HTTPHEADER      => $headers
          ]);

        switch($metodo){
            case 'POST':
            case 'PUT':
                curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($request));
                break;
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($response) {
            return json_decode($response, true);
          }else{
            return $err;
          }
       
       
    }




















}







?>