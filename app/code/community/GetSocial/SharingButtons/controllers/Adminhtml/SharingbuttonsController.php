<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    GetSocial
 * @package     GetSocial_SharingButtons
 * @copyright   Copyright (c) 2015 GetSocial (http://getsocial.io/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php
class GetSocial_SharingButtons_Adminhtml_SharingButtonsController extends Mage_Adminhtml_Controller_Action
{

	private static $gs_url;
	private static $gs_account;
	private static $gs_url_api;
	private static $api_url;	

    static function initUrls()
	{

		// Local
	    // self::$gs_url = "//127.0.0.1:3001";
	    // self::$gs_account = "http://127.0.0.1:3000/";
	    // self::$gs_url_api = "http://127.0.0.1:3001";
	    // self::$api_url = "http://127.0.0.1:3000/api/v1/";

	    // Staging
	    // self::$gs_url = "http://staging.api.at.getsocial.io";
	    // self::$gs_account = "http://staging.account.getsocial.io/";
	    // self::$gs_url_api = "//staging.api.at.getsocial.io";
	    // self::$api_url = "http://staging.account.getsocial.io/api/v1/";
	    
	    // Production
	    self::$gs_url = "https://api.at.getsocial.io";
	    self::$gs_account = "https://getsocial.io/";
	    self::$gs_url_api = "//api.at.getsocial.io";
	    self::$api_url = "https://getsocial.io/api/v1/";
	}

	protected function _initAction() {
		
		$this->loadLayout()
			->_setActiveMenu('configgetsocial/managesharingbuttons')
			->_addBreadcrumb(Mage::helper('adminhtml')->__("Links Manager"), Mage::helper('adminhtml')->__("Links Manager"));
		
		return $this;
	} 

	public function indexAction() {

		$this->_initAction();
		
		$this->getLayout()->getBlock('sharingbuttons')
			->setData('gs_url', self::$gs_url)
			->setData('gs_account', self::$gs_account)
			->setData('gs_url_api', self::$gs_url_api)
			->setData('api_url', self::$api_url);
		$this->renderLayout();
	}

	public function postAction()
    {
    	$data = $this->getRequest()->getPost();
    	
    	Mage::log(print_r($data, 1), null, 'logfile.log');

  		// $model = Mage::getModel('sharingbuttons/sharingbuttons');
		
		$api_data = json_decode($data['api-result']);

		Mage::log(print_r($api_data->gs_apps, 1), null, 'logfile.log');

        if (sizeof(Mage::getModel('core/variable')->loadByCode('gs-api-key')->getData()) == 0) {
			
			Mage::getModel('core/variable')
				->setCode('gs-api-key')
				->setPlainValue($data['gs-api-key'])
				->save();

			Mage::getModel('core/variable')
				->setCode('gs-identifier')
				->setPlainValue($api_data->identifier)
				->save();

			Mage::getModel('core/variable')
				->setCode('gs-pro')
				->setPlainValue(json_encode($api_data->pro))
				->save();

			Mage::getModel('core/variable')
				->setCode('gs-apps')
				->setPlainValue(json_encode($api_data->gs_apps))
				->save();

			Mage::getModel('core/variable')
				->setCode('gs-url-api')
				->setPlainValue(json_encode(self::$gs_url_api))
				->save();
		}

  		$this->_redirect('/*');
    }
}

GetSocial_SharingButtons_Adminhtml_SharingButtonsController::initUrls();
