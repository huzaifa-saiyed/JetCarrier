<?php

namespace Kitchen365\JetCarrier\Model;

class JetCarrier extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Kitchen365\JetCarrier\Model\ResourceModel\JetCarrier::class);
    }
}
