<?php
/**
 * 2007-2016 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2016 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace UOL\PagSeguro\Controller\Adminhtml\Abandoned;

use UOL\PagSeguro\Controller\Adminhtml\Conciliation;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Conciliation
 * @package UOL\PagSeguro\Controller\Adminhtml
 */
class Index extends \Magento\Backend\App\Action
{

    /**
     * Result page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        if (!$this->_isAccessible())
            return $this->_redirect('pagseguro/abandoned/error');

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Abandonadas'));
        $resultPage->getLayout()->getBlock('adminhtml.block.pagseguro.abandoned.content')->setData('adminurl', $this->getAdminUrl());
        return $resultPage;
    }

    /**
     * News access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('UOL_PagSeguro::Abandoned');
    }


    /**
     * Check if abandoned is available in config
     *
     * @return mixed
     */
    private function _isAccessible()
    {
        //Get instanceof \Magento\Framework\App\Config\ScopeConfigInterface
        $scopeConfig = $this->_objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');

        return $scopeConfig->getValue('payment/pagseguro/abandoned_active');
    }

    /**
     * Generate Admin Url
     *
     * @return string
     */
    private function getAdminUrl()
    {
        //Get objects
        $configReader = $this->_objectManager->create('Magento\Framework\App\DeploymentConfig\Reader');
        $storeManager = $this->_objectManager->create('Magento\Store\Model\StoreManagerInterface');

        // Load config
        $config = $configReader->load();
        // Get front name
        $adminSuffix = $config['backend']['frontName'];

        return sprintf("%s%s", $storeManager->getStore()->getBaseUrl(), $adminSuffix);
    }
}