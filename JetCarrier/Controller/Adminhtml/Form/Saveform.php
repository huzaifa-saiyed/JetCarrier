<?php

namespace Kitchen365\JetCarrier\Controller\Adminhtml\Form;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Kitchen365\JetCarrier\Model\JetCarrierFactory;

class Saveform extends Action
{
    protected $jetcarrierFactory;

    public function __construct(
        Context $context,
        JetCarrierFactory $jetcarrierFactory
    ) {
        $this->jetcarrierFactory = $jetcarrierFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $varOne = $this->getRequest()->getPostValue();

        $id = !empty($varOne['entity_id']) ? (int)$varOne['entity_id'] : null;
        $varModel = $this->jetcarrierFactory->create();
        if ($id) {
            $varModel->load($id);

            if (!$varModel->getId()) {
                $this->messageManager->addErrorMessage(__('This Data no longer exists.'));
                return $resultRedirect->setPath('jetcarrier/index/index');
            }
        }
        $varModel->setZipcode($varOne['zipcode'])
            ->setSvx($varOne['svx'])
            ->setStx($varOne['stx'])
            ->setLtx($varOne["ltx"])
            ->setFtl($varOne["ftl"]);

        try {
            $varModel->save();
            $this->messageManager->addSuccessMessage(__('data has been saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the user data.'));
            $this->_getSession()->setFormData($varOne);
            $this->_redirect('jetcarrier/index/index');
        }
        $this->_redirect('jetcarrier/index/index');
    }
}
