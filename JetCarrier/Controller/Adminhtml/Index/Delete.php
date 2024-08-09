<?php

namespace Kitchen365\JetCarrier\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Kitchen365\JetCarrier\Model\JetCarrierFactory;

class Delete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected $jetcarrierFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        JetCarrierFactory $jetcarrierFactory,
    ) {
        parent::__construct($context);
        $this->jetcarrierFactory = $jetcarrierFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');

        if ($id) {
            try {

                $pageModel = $this->jetcarrierFactory->create()->load($id);
                $pageModel->delete();

                $this->messageManager->addSuccessMessage(__('The item has been deleted.'));
            } catch (\Exception  $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        $this->_redirect('jetcarrier/index/index');
    }
}
