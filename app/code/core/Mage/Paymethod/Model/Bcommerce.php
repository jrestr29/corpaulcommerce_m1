<?php
/**
 * Magento
 * Ejecuta servicio Bcommerce (Boton de pago)
 *
 * @author     Ejepe
 */
class Bcommerce_Paymethod_Model_Bcommerce extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'paymethod';
	
	protected $_formBlockType = 'paymethod/form_paymethod'; 
 	protected $_infoBlockType = 'paymethod/info_paymethod';
	protected $_canUseCheckout = true;
	protected $_canUseForMultishipping  = true;
	
	/**
	* Retorna url de redireccion del botón de pago
	* @author     Ejepe
	*/
	public function getOrderPlaceRedirectUrl()
    {
		return Mage::getUrl('paymethod/payment/redirect', array('_secure' => false));
	}
	
	/**
	 * Retorna parametros del Vpos para hacer el llamado
	 * @author     Ejepe
	*/
	public function getParametrosVpos()
    {
		// WEB Service
		$response = "";
		$urlVpos = "";
		$adquirients = "";
		$codcomers = "";
		$numterminal = "";
		$XMLRES = "";
		$SIGN = "";
		$SESSION = "";
		try {
			$client = new SoapClient (Mage::getStoreConfig('payment/paymethod/url'));
			$adquirients = "1";
			$codcomers = Mage::getStoreConfig('payment/paymethod/commerce_id');
			$numterminal = Mage::getStoreConfig('payment/paymethod/terminal_id');
			$checkout = Mage::getSingleton('checkout/session');
			$customer = $checkout->getCustomer();
			
			$orderIncrementId = $checkout->getLastRealOrderId();
			$numtx = $orderIncrementId;
			$data = array
			(
				'adquirients'=>$adquirients,
				'codcomers'=>$codcomers,
				'numterms'=>$numterminal,
				'numOrden'=>$numtx
				
			);
			$response = $client->getParametrosEnviadosVPos($data);
			
			$urlVpos = $response->getParametrosEnviadosVPosReturn[0];
			$XMLRES = $response->getParametrosEnviadosVPosReturn[4];
			$SIGN = $response->getParametrosEnviadosVPosReturn[5];
			$SESSION = $response->getParametrosEnviadosVPosReturn[6];
			
		}catch (Exception $e) {
			$response = $e->getMessage();
			print_r($response);
			Mage::throwException($response);
			return null;
		}
		
        return array(
  			  'IDACQUIRER' => $adquirients
			, 'IDCOMMERCE' => $codcomers
			, 'TERMINALCODE' => $numterminal
			, 'XMLREQ' => $XMLRES
			, 'DIGITALSIGN' => $SIGN
			, 'SESSIONKEY' => $SESSION
			, 'ACTION' => $urlVpos
		);
    }
    
	/**
	 * Retorna HTML con botón de pagos
	 * @author     Ejepe
	*/
    public function getParams()
    {	
		$adquiriente = "1";
		// numero del comercio
		$codcomerc = Mage::getStoreConfig('payment/paymethod/commerce_id');
		// numero de la terminal de pago
		$numterminal = Mage::getStoreConfig('payment/paymethod/terminal_id');
		$tmp = strlen($numterminal);
		while($tmp < 8){
			$numterminal = '0'.$numterminal;
			$tmp = $tmp + 1;
		}
		
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$quote = Mage::getModel('checkout/session')->getQuote();
		$quoteData= $quote->getData();
		
		// Genero
		$gender = $customer->getGender();
		$genero = "";
		if ($gender == "1") {
			$genero = "M";
		} else {
			$genero = "F";
		}
		$amount = $quoteData['grand_total'];
		
		$iva = Mage::helper('checkout')->getQuote()->getShippingAddress()->getData('tax_amount');
		if($iva == '0')
		{
			//$iva = $quoteData['grand_total'] * 0.16;
		}
		$ivareturn = $iva;
		
		$amount = number_format($amount,2);
		$amount = str_replace('.', '', $amount);
		$amount = str_replace(',', '', $amount);			
		$iva = number_format($iva,2);
		$iva = str_replace('.', '', $iva);
		$iva = str_replace(',', '', $iva);		
		
		// direccion ip
		if (! empty ( $_SERVER ['HTTP_CLIENT_IP'] )) {
			$ipaddress = $_SERVER ['HTTP_CLIENT_IP'];
		} elseif (! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
			$ipaddress = $_SERVER ['HTTP_X_FORWARDED_FOR'];
		} else {
			$ipaddress = $_SERVER ['REMOTE_ADDR'];
		}
		// Reserva un número de Orden
		$quote->reserveOrderId();
		$numtx = $quote->getReservedOrderId();
		$numtx = $numtx + 1;
		
		// direccion de facturacion del cliente
		$direccionFacturacionArreglo = $quote->getBillingAddress()->getData();
		$direccion = $direccionFacturacionArreglo['street'];
		$ciudad = $direccionFacturacionArreglo['city'];
		$telefono = $direccionFacturacionArreglo['telephone'];
		$pais = $direccionFacturacionArreglo['country_id'];
		$state = $direccionFacturacionArreglo['region'];
		$postcode = $direccionFacturacionArreglo['postcode'];
		$tmp = strlen($telefono);
		while($tmp < 10){
			$telefono = '0'.$telefono;
			$tmp = $tmp + 1;
		}
		$celular = ""; // Magento no registra el celular
		if($celular==""){
			$celular = "000000000";
		}

		// direccion envio
		$direccionEnvioArreglo = $quote->getShippingAddress()->getData();
		$nombreEnvio = $direccionEnvioArreglo['firstname'];
		$apellidoEnvio = $direccionEnvioArreglo['lastname'];
		$direccion_envio = $direccionEnvioArreglo['street'];
		$ciudad_envio = $direccionEnvioArreglo['city'];
		$pais_envio = $direccionEnvioArreglo['country_id'];
		$state_envio = $direccionEnvioArreglo['region'];
		$postcode_envio = $direccionEnvioArreglo['postcode'];
		$telefono_envio = $direccionEnvioArreglo['telephone'];
		$celular_envio = "";
		$nacionalidad = "CO";
		
		// nombre cliente
		if(!empty($customer->getFirstname()))
		{
			$nombre = $customer->getFirstname();
			if(!empty ($customer->getMiddlename()))
			{
				$nombre .= " " . $customer->getMiddlename();
			}
		}
		else
		{
			$nombre = $nombreEnvio;
			if(!empty ($direccionEnvioArreglo['middlename']))
			{
				$nombre .= " " . $direccionEnvioArreglo['middlename'];
			}
		}
		// apellidos del cliente
		if(!empty($customer->getLastname()))
		{
			$apellidos = $customer->getLastname();
		}
		else
		{
			$apellidos = $apellidoEnvio;
		}
		
		// email cliente
		if(!empty($customer->getEmail()))
		{
			$email = $customer->getEmail();
		}
		else
		{
			$email = $direccionEnvioArreglo['email'];
		}
		
		$data = array(
			'adquiriente'=>'1',
			'transactionTrace'=>'BC',
			'nacionalidad'=>$nacionalidad,
			'codigopais'=>$pais,
			'numtx'=>$numtx,
			'direccion'=>$direccion,
			'ipaddress'=>$ipaddress,
			'amount'=>$amount,
			'tasaaero'=>'',
			'iva'=>$iva,
			'ivareturn'=>$ivareturn,
			'nombres'=>$nombre,
			'apellidos'=>$apellidos,
			'numterminal'=>$numterminal,
			'codcomerc'=>$codcomerc,
			'email'=>$email,
			'genero'=>$genero,
			'ciudad'=>$ciudad,
			'celular'=>$celular,
			'telefono'=>$telefono,
			'paisenvio'=>$pais_envio,
			'ciudadenvio'=>$ciudad_envio,
			'direccionenvio'=>$direccion_envio,
			'fingerprint'=>$numtx,
			'postcode'=>$postcode,
			'state'=>$state,
			'observaciones'=>'Comprobante de pago. Pedido # '.$numtx,
			'monto'=>$amount,
			'cuotas'=>'001',
			'opcionales'=>'',
			'passengerFirstName'=>'',
			'passengerLastName'=>'',
			'passengerDocumentType'=>'',
			'passengerDocumentNumber'=>'',
			'passengerAgencyCode'=>'',
			'airportCode'=>'',
			'airportCity'=>'',
			'airportCountry'=>'',
			'airportCodeLlegada'=>'',
			'airportCityLlegada'=>'',
			'airportCountryLlegada'=>'',
			'administrativeRateAmount'=>'',
			'administrativeRateIva'=>'',
			'administrativeRateIvaReturn'=>'',
			'administrativeRateCode'=>'',
			'flightAirlineCode'=>'',
			'flightDepartureAirport'=>'',
			'flightArriveAirport'=>'',
			'flightDepartureDate'=>'',
			'flightDepartureTime'=>'',
			'flightArriveDate'=>'',
			'flightArriveTime'=>'',
			'flightReservation'=>'',
			'flightDepartureIata'=>'',
			'flightArriveIata'=>'',
			'propina'=>'',
			'iac'=>'',
			'domicilio'=>'',
			'nombreEnvio'=>$nombreEnvio,
			'apellidoEnvio'=>$apellidoEnvio,
			'postcodee'=>$postcode_envio,
			'statee'=>$state_envio
		);
		
		// WEB Service
		$response = "";
		try {
			$client = new SoapClient (Mage::getStoreConfig('payment/paymethod/url'));
			$response = $client->realizarpago ($data);
		}catch (Exception $e) {
			$response = $e->getMessage();
			print_r($response);
			Mage::throwException($response);
			return "";
		}
		return $response; 
    }
	
	public function isAvailable($quote=null)
    {
        return true;
    }
}
