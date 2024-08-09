<?php

namespace Kitchen365\JetCarrier\Model\ResourceModel;

class JetCarrier extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('jet_carrier_prices', 'entity_id');
    }
}
