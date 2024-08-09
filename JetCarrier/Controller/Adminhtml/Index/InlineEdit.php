<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kitchen365\JetCarrier\Controller\Adminhtml\Index;


use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Kitchen365\JetCarrier\Model\JetCarrierFactory;
use Kitchen365\JetCarrier\Model\ResourceModel\JetCarrier as JetCarrierResourceModel;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $jsonFactory;
    private $jetcarrierFactory;
    private $jetcarrierResourceModel;

    public function __construct(
        Context $context,
        JetCarrierFactory $jetcarrierFactory,
        JsonFactory $jsonFactory,
        JetCarrierResourceModel $jetcarrierResourceModel
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->jetcarrierFactory = $jetcarrierFactory;
        $this->jetcarrierResourceModel = $jetcarrierResourceModel;
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $entityId) {
                    $varInlineEdit = $this->jetcarrierFactory->create();
                    $this->jetcarrierResourceModel->load($varInlineEdit, $entityId);
                    $varInlineEdit->setData(array_merge($varInlineEdit->getData(), $postItems[$entityId]));
                    $this->jetcarrierResourceModel->save($varInlineEdit);
                }
            }
        }

        return $resultJson->setData(
            [
                'messages' => $messages,
                'error' => $error
            ]
        );
    }
}
