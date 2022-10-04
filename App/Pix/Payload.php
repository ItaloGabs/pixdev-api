<?php
namespace App\Pix;
/**
* 
* Chave do pix: $pixKey
* Descrição que vai aparecer para o cliente na hora do pagamento: $description
* Nome do titular da conta: $merchantName
* Cidade do titular da conta: $merchantCity
* Id da transação pix: $txid
* Valor do pix: $amount
* Define se o pagamento deve ser feito apenas uma vez: $unicopagamento
* URL payload dinamico: $url
*
* @var string
*/


// Vai receber os dados do usuario e outros dados que são fixos(IDs do payload)
class Payload{

    private $pixKey;
    private $description;
    private $merchantName;
    private $merchantCity;
    private $txid;
    private $amount;
    private $unicopagamento = false;
    private $url;

    /**
     * IDs do Payload do Pix(Fixos)
    * @var string
    */
    const ID_PAYLOAD_FORMAT_INDICATOR                  = '00';
    const ID_POINT_OF_INITIATION_METHOD                = '01';
    const ID_MERCHANT_ACCOUNT_INFORMATION              = '26';
    const ID_MERCHANT_ACCOUNT_INFORMATION_GUI          = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION_KEY          = '01';
    const ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION  = '02';
    const ID_MERCHANT_ACCOUNT_INFORMATION_URL          = '25';
    const ID_MERCHANT_CATEGORY_CODE                    = '52';
    const ID_TRANSACTION_CURRENCY                      = '53';
    const ID_TRANSACTION_AMOUNT                        = '54';
    const ID_COUNTRY_CODE                              = '58';
    const ID_MERCHANT_NAME                             = '59';
    const ID_MERCHANT_CITY                             = '60';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE            = '62';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID       = '05';
    const ID_CRC16                                     = '63';
    

    /**
     * Método responsável por definir o valor de $pixkey
    * @param string $pixKey
    */
    public function setPixKey($pixKey){
        $this->pixKey = $pixKey;
        return $this;
    }

    /**
     * Método responsável por definir o valor de $description
    * @param string $description
    */
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    /**
     * Método responsável por definir o valor de $url
    * @param string $description
    */
    public function setUrl($url){
        $this->url = $url;
        return $this;
    }

    /**
     * Método responsável por definir o valor de $unicopagamento
    * @param bool $description
    */
    public function setUnicoPagamento($unicopagamento){
        $this->unicopagamento = $unicopagamento;
        return $this;
    }

    /**
     * Método responsável por definir o valor de $merchantName
    * @param string $merchantName
    */
    public function setMerchantName($merchantName){
        $this->merchantName = $merchantName;
        return $this;
    }

    /**
     * Método responsável por definir o valor de $merchantCity
    * @param string $merchantCity
    */
    public function setMerchantCity($merchantCity){
        $this->merchantCity = $merchantCity;
        return $this;
    }

    /**
     * Método responsável por definir o valor de $txid
    * @param string $pixKey
    */
    public function setTxid($txid){
        $this->txid = $txid;
        return $this;
    }

    /**
     * Método responsável por definir o valor de $amount
    * @param float $amount
    */
    public function setAmount($amount){
        $this->amount = (string)number_format($amount,2,'.','');
        return $this;
    }

    


#########################################################################################################


    /**
     * Método responsável por retornar o valor completo de um object do payload
    * @param
    */
    private function getValueCodPix($id,$value){
        $size = str_pad(mb_strlen($value),2,'0',STR_PAD_LEFT);
        return $id.$size.$value;
    }

    /**
     * Retorna as informações da conta
    * @param string
    */
    private function getMerchantAccountInformation(){
        //Dominio do banco
        $identificadorBank = $this->getValueCodPix(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI,'br.gov.bcb.pix');

        //chave pix
        $key = (strlen($this->pixKey)) ? $this->getValueCodPix(self::ID_MERCHANT_ACCOUNT_INFORMATION_KEY,$this->pixKey) : '';

        //descrição do pagamento
        //ternario if
        $description = (strlen($this->description)) ? $this->getValueCodPix(self::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION,$this->description) : '';

        //URl do qrcode dinamico
        //ternario if
        $url = (strlen($this->url)) ? $this->getValueCodPix(self::ID_MERCHANT_ACCOUNT_INFORMATION_URL,preg_replace('/^https?\:\/\//','',$this->url)) : '';

        // Retorna o valor completo da conta
        return $this->getValueCodPix(self::ID_MERCHANT_ACCOUNT_INFORMATION,$identificadorBank.$key.$description.$url);
    }

    /**
     * Método responsável por retornar os valores completos do campo adicional do pix(TXID)
    * @param
    */
    private function getCampoAdicional(){
        //TXID
        $txid = $this->getValueCodPix(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID,$this->txid);

        return $this->getValueCodPix(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE,$txid);
    }

    private function getUnicoPagamento(){
        return $this->unicopagamento ? $this->getValueCodPix(self::ID_POINT_OF_INITIATION_METHOD,'12') : '';
    }

#######################################################################################################################



    /**
     * Método responsável por gerar o codígo completo do payload pix
    * @param
    */
    public function getPayload(){
        //Cria o payload
        $payload = $this->getValueCodPix(self::ID_PAYLOAD_FORMAT_INDICATOR,'01').
                   $this->getUnicoPagamento().
                   $this->getMerchantAccountInformation().
                   $this->getValueCodPix(self::ID_MERCHANT_CATEGORY_CODE,'0000').
                   $this->getValueCodPix(self::ID_TRANSACTION_CURRENCY,'986').
                   $this->getValueCodPix(self::ID_TRANSACTION_AMOUNT,$this->amount).
                   $this->getValueCodPix(self::ID_COUNTRY_CODE,'BR').
                   $this->getValueCodPix(self::ID_MERCHANT_NAME,$this->merchantName).
                   $this->getValueCodPix(self::ID_MERCHANT_CITY,$this->merchantCity).
                   $this->getCampoAdicional();

        // Retorna o payload + CRC16 que é o finalzinho do metodo
        return $payload.$this->getCRC16($payload);
    }

      /**
     * Método responsável por calcular o valor da hash de validação do código pix
     * @return string
     */
    private function getCRC16($payload) {
        //ADICIONA DADOS GERAIS NO PAYLOAD
        $payload .= self::ID_CRC16.'04';

        //DADOS DEFINIDOS PELO BACEN
        $polinomio = 0x1021;
        $resultado = 0xFFFF;

        //CHECKSUM
        if (($length = strlen($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $resultado ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($resultado <<= 1) & 0x10000) $resultado ^= $polinomio;
                    $resultado &= 0xFFFF;
                }
            }
        }

        //RETORNA CÓDIGO CRC16 DE 4 CARACTERES
        return self::ID_CRC16.'04'.strtoupper(dechex($resultado));
    }


}
?>