<?php

namespace Kitchen365\JetCarrier\Model\ResourceModel\JetCarrier;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Kitchen365\JetCarrier\Model\JetCarrier::class,
            \Kitchen365\JetCarrier\Model\ResourceModel\JetCarrier::class
        );
    }
}
