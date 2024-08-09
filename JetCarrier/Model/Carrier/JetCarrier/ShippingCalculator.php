<?php

namespace Kitchen365\JetCarrier\Model\Carrier\JetCarrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class ShippingCalculator
{
    protected $scopeConfig;
    protected $palletHelpr;
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \KitchenOms\PalletFee\Helper\PalletData $palletHelpr
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->palletHelpr = $palletHelpr;
    }

    public function getConfigData($path)
    {
        return $this->scopeConfig->getValue(
            'carriers/jet_carrier/'.$path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCalculatePrice($request)
    {
       $type = $this->getCalculationType($request);
       switch($type)
       {
            case "SVX" : {
                $price = $this->getValueByPostcodeAndKey($request->getDestPostcode(), 'SVX');
                break;
            }
            case "STX" : {
                $price = $this->getValueByPostcodeAndKey($request->getDestPostcode(), 'STX');
                break;
            }
            case "LTX" : {
                $price = $this->getValueByPostcodeAndKey($request->getDestPostcode(), 'LTX');
                break;
            }
            case "FTL" : {
                $price = $this->getValueByPostcodeAndKey($request->getDestPostcode(), 'FTL');
                break;
            }
            case "FTL_MORE" : {
                $price = $this->getValueByPostcodeAndKey($request->getDestPostcode(), 'FTL_MORE');
                break;
            }
            default: {
                $price = 0;
            }
       }
       return $price;
    }

    public function getCalculationType($request)
    {
        $type = '';
        if($request->getPackageWeight() >= $this->getConfigData('package_max_weight') && $request->getPackageWeight() <= $this->getConfigData('package_max_weight'))
        {
            $type = 'SVX';
        } else {
            $palletCount = $this->getPalletCount($request);
            if($palletCount > 0)
            {
                if($palletCount >= 1 && $palletCount <= 4) {
                    $type = 'STX';
                } else if($palletCount >= 5 && $palletCount <= 9) {
                    $type = 'LTX';
                } else if($palletCount >= 10 && $palletCount <= 12) {
                    $type = 'FTL';
                } else if($palletCount > 12) {
                    $type = 'FTL_MORE';
                }
            }
        }
        return $type;
    }

    public function getPalletCount($request)
    {
        $quote = $this->getQuote($request);
        $palletData = $this->palletHelpr->getPalletCalculation($quote,true);
        $palletCount = 0;
        if(isset($palletData['pallet']) && !empty($palletData['pallet']))
        {
            foreach($palletData['pallet'] as $pallet)
            {
                $palletCount += $pallet['pallet_count'];
            }
        }
        return $palletCount;
    }

    public function getQuote($request)
    {
        foreach($request->getAllItems() as $item)
        {
            return $item->getQuote();
        }
    }

    public function getValueByPostcodeAndKey($postcode, $key) {
        $tableData = $this->tableData();
        foreach ($tableData as $item) {
            if ($item['postcode'] == $postcode) {
                return $item[$key] ?? null;
            }
        }
        return null;
    }

    public function tableData()
    {
        return [
            [
                'postcode' => 75234,
                'SVX' => 20.00,
                'STX' => 60.00,
                'LTX' => 90.00,
                'FTL' => 134.00
            ],
            [
                'postcode' => 75235,
                'SVX' => 45.00,
                'STX' => 122.00,
                'LTX' => 176.00,
                'FTL' => 264.00
            ],
        ];
    }
}
