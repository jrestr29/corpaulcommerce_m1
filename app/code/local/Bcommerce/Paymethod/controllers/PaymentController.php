<?php
/**
 * Magento
 * Muestra Voucher y configura llamado al Vpos
 *
 * @author     Ejepe
 */
class Bcommerce_Paymethod_PaymentController extends Mage_Core_Controller_Front_Action {

	/**
	* Hace el llamado al Vpos, enviandole los parametros generados
	* @author     Ejepe
	**/
	public function redirectAction()
	{
        $bcommerce = Mage::getModel('paymethod/bcommerce');
        
        $fields = $bcommerce->getParametrosVpos();

        $form = new Varien_Data_Form();
        $form->setAction( $fields['ACTION'] )
            ->setId('frmSolicitudPago')
            ->setName('frmSolicitudPago')
            ->setMethod('POST')
            ->setUseContainer(true);
			
		foreach ($fields as $field=>$value) {
			if ($field != 'ACTION')
				$form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }
        $html = '<html><body>';
        $html.= $this->__('Usted sera redireccionado a la página de pago de Credibanco en unos segundos');
        $html.= $form->toHtml();
		$html.= '<img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'pagosonline.gif" alt="">';
        $html.= '<script type="text/javascript">document.getElementById("frmSolicitudPago").submit();</script>';
        $html.= '</body></html>';

        echo $html;
	}
	
	
	
	/**
	* 
	
	public function successAction()
	{
		$merchant_id = Mage::getStoreConfig( 'payment/payu/merchant_id' );
		$secure_key = Mage::getStoreConfig( 'payment/payu/secure_key' );
		
		$session = Mage::getSingleton('checkout/session');
		
		$referenceCode = $_GET['referenceCode'];
		$transactionState = $_GET['transactionState'];
		$transaction_id = $_GET['transaction_id'];
		$signature = $_GET['signature'];
		$currency = $_GET['currency'];
		$TX_VALUE = $_GET['TX_VALUE'];
		$value = number_format($TX_VALUE, 1, '.', '');
		
		$hash_signature = md5($secure_key . '~' . $merchant_id . '~' . $referenceCode . '~' . $value . '~' . $currency . '~' . $transactionState );
		
		if(($transactionState == 4 || $transactionState == 7) && $hash_signature == $signature){
				$this->_redirect('checkout/onepage/success');
		} else {
				$this->_redirect('checkout/onepage/failure');
		}

	}
	*/
	
	public function responseAction() 
    {
		
		if ($this->getRequest()->get("XMLRES")) 
		{
			$codcomers = Mage::getStoreConfig('payment/paymethod/commerce_id');
			$numterminal = Mage::getStoreConfig('payment/paymethod/terminal_id');
			$codunico = Mage::getStoreConfig('payment/paymethod/cod_unico_comercio');
			$data = array(
				'adquirients' => '1',
				'codcomers' => $codcomers,
				'DIGITALSIGN'=> $this->getRequest()->get("DIGITALSIGN"),
				'numterms'=> $numterminal ,
				'XMLRES'=>$this->getRequest()->get("XMLRES"),
				'SESSIONKEY'=>$this->getRequest()->get("SESSIONKEY")
			);
			
			//WEB Service
			$client = new SoapClient (Mage::getStoreConfig('payment/paymethod/url'));
			$response = $client->consultaroperacion ($data);
			$datapro = $response->consultaroperacionReturn;
			date_default_timezone_set('America/Bogota'); 
			$tiempo = date("d-m-Y h:i:sa");
			$split = preg_split("[ ]",$tiempo);
			
			
			if($datapro[7] == "00" ){
				$estadoPro = '2';
			}else{
				$estadoPro = '15';
			}
			if($datapro[13]=='170'){
				$moneda='COP';
			}else{
				$moneda='';
			}
			
			$len = strlen($datapro[9]);
			$valor_total=number_format(substr($datapro[9],0,$len-2).".".substr($datapro[9],$len-2,$len),2,'.',',');
			
			$len = strlen($datapro[10]);
			$valor_iva=number_format(substr($datapro[10],0,$len-2).".".substr($datapro[10],$len-2,$len),2,'.',',');

			$len = strlen($datapro[11]);
			$valor_return=number_format(substr($datapro[11],0,$len-2).".".substr($datapro[11],$len-2,$len),2,'.',',');
			
			$pay = new SimpleXMLElement($datapro[17]);
			$additionalObservations = $pay->additionalObservations;
			$propinaarray = $pay->taxes;
			$price_propina = 0;
			
			(double)$neto = (double)$datapro[9]-(double)$datapro[10];
			
			$len = strlen($neto);
			$neto=number_format(substr($neto,0,$len-2).".".substr($neto,$len-2,$len),2,'.',',');
			
			$len = strlen($pay->cardNumber[0]);
			$tarjeta = substr($pay->cardNumber[0],$len-4,$len);
			
			// Magento no maneja Referencia de Comercio ni de Pago
			$voucher =
				array(
					'_secure' => true,
					'css' => '../css/bcommerce.css',
					'tarjeta'=>'************'.$tarjeta
				);
				
			if(!empty(trim($voucher['valid'])))			$voucher['valid'] = (bool)$datapro[10];
			if(!empty(trim($datapro[2])))					$voucher['terminal'] = $datapro[2];		
			if(!empty(trim(Mage::getStoreConfig('payment/paymethod/nombre_establecimiento'))))		$voucher['terminal'] = Mage::getStoreConfig('payment/paymethod/nombre_establecimiento');
			if(!empty(trim($codcomers)))					$voucher['codunico'] = $codcomers;
			if(!empty(trim($datapro[1])))					$voucher['numtra'] = $datapro[1];
			if(!empty(trim($split[0])))					$voucher['fectra']= $split[0];
			if(!empty(trim($split[1])))					$voucher['hortra'] = $split[1];
			if(!empty(trim($moneda)))						$voucher['moneda'] = $moneda;
			if(!empty(trim($valor_total)))				$voucher['valtot'] = ' '.$valor_total;
			if(!empty(trim($valor_iva)))					$voucher['iva'] = ' '.$valor_iva;
			if(!empty(trim($valor_return)))				$voucher['basereturn'] = ' '.$valor_return;
			if(!empty(trim($neto)))						$voucher['valnet'] = ' '.$neto;
			if(!empty(trim($additionalObservations)))		$voucher['descr'] = str_replace( '#', 'No.',(string)$additionalObservations);
			if(!empty(trim($datapro[7])))					$voucher['respuesta'] = $datapro[7];
			if(!empty(trim($datapro[3])))					$voucher['numaut'] = $datapro[3];
			if(!empty(trim($datapro[8])))					$voucher['desresp'] = $datapro[8];
			if(!empty(trim($pay->quotaCode)))				$voucher['cuotas'] = (string)$pay->quotaCode;
			if(!empty(trim($codunico)))					$voucher['codunicocomercio'] = $codunico;
			if(!empty(trim($pay->cardType)))				$voucher['cardType'] = (string)$pay->cardType;
			
				
			// Actualiza estado pedido			
			$order = Mage::getModel('sales/order')->loadByIncrementId($datapro[1]);
			$order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, 'Payment Success.');
			$order->save();
			
			unset($datapro,$pay,$tarjeta,$valor_iva,$valor_return,$valor_total,$len,$moneda,$additionalObservations);
			
			// Muestra Voucher
			/*Mage::getSingleton('checkout/session')->unsQuoteId();
			
			$form = new Varien_Data_Form();
			$form->setAction( 'paymethod/success' )
				->setId('bcommerce_voucher')
				->setName('bcommerce_voucher')
				->setMethod('POST')
				->setUseContainer(true);
				
			foreach ($voucher as $field=>$value) {
				$form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
			}
			$html = '<html><body>';
			$html.= $this->__('Usted sera redireccionado a la página de pago de Credibanco en unos segundos');
			$html.= $form->toHtml();
			$html.= '<img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'pagosonline.gif" alt="">';
			$html.= '<script type="text/javascript">document.getElementById("bcommerce_voucher").submit();</script>';
			$html.= '</body></html>';

			echo $html;*/
			
			$this->_redirect('checkout/onepage/success', $voucher);
		}
		else
		{
			// Muestra error de Magento
			$this->_redirect('checkout/onepage/failure');
		}
    }
	/*
	public function notifyAction()
	{
		$merchant_id = Mage::getStoreConfig( 'payment/payu/merchant_id' );
		$secure_key = Mage::getStoreConfig( 'payment/payu/secure_key' );

		
		$reference_sale = $_POST['reference_sale'];
		$state_pol = $_POST['state_pol'];
		$transaction_id = $_POST['transaction_id'];
		$signature = $_POST['sign'];
		$currency = $_POST['currency'];
		$value = $_POST['value'];
		
		$split = explode('.', $value);
		$decimals = $split[1];
		if ($decimals % 10 == 0) {
			$value = number_format($value, 1, '.', '');
		}
		
		$return_message = $_POST['response_message_pol'];
		
		$hash_signature = md5($secure_key . '~' . $merchant_id . '~' . $reference_sale . '~' . $value . '~' . $currency . '~' . $state_pol );
		if(($state_pol == 4 || $state_pol == 7) && $hash_signature == $signature){
				$order = Mage::getModel('sales/order')->loadByIncrementId($reference_sale);
				
				$order->getPayment()->setTransactionId($transaction_id);
				
				$order_comment = $return_message;
				foreach($_POST as $key=>$value){
					$order_comment .= "<br/>$key: $value";
				}
				
				if($state_pol == 4){
					$order->getPayment()->registerCaptureNotification( $_POST['value'] );
					$order->addStatusToHistory($order->getStatus(), $order_comment);
				}elseif($state_pol == 7){
					$order->addStatusToHistory('pending', $order_comment);
				}
				
				//$order->addStatusToHistory($order->getStatus(), $order_comment);
					
				$order->save();
				
		} else {
				$order = Mage::getModel('sales/order')->loadByIncrementId($reference_sale);
				$order->cancel();
        		$order->addStatusToHistory($order->getStatus(), $return_message);
				$order->save();
		}
		
		exit;

	}
	**/
}