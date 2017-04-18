<?php

namespace MagicToolbox\MagicZoom\Block\Adminhtml\Settings;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'object_id';
        $this->_controller = 'adminhtml_settings';
        $this->_blockGroup = 'MagicToolbox_MagicZoom';
        $this->_headerText = 'Magic Zoom Config';

        parent::_construct();

        $this->_formScripts[] = '
            require([\'magiczoom\'], function(magiczoom){
                magiczoom.initSettings();
            });
        ';

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->updateButton('save', 'label', __('Save Settings'));
    }
}
