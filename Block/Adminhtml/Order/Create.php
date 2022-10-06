<?php
/**
 * Customer Identification Document
 *
 * @category   Mugar
 * @package    Mugar_CustomerIdentificationDocument
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author     Mugar (https://www.mugar.io/)
 *
 */

namespace Mugar\CustomerIdentificationDocument\Block\Adminhtml\Order;

use Magento\Framework\View\Element\Template;
use Mugar\CustomerIdentificationDocument\Api\Data\CidFieldsInterface;
use Mugar\CustomerIdentificationDocument\Helper\Data;

/**
 * @method Create setCidType($string)
 * @method string getCidType()
 */
class Create extends Template
{
    private Data $helper;

    /**
     * @param Template\Context $context
     * @param Data             $helper
     * @param array            $data
     */
    public function __construct(
        Template\Context $context,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    public function getSelectId()
    {
        return sprintf('%s-cid-type', $this->getCidType());
    }

    public function getSelectName()
    {
        return sprintf('order[%s]', $this->getCidType() === Data::BLOCK_TYPE_SHIPPING ? CidFieldsInterface::SHIPPING_CID_TYPE : CidFieldsInterface::BILLING_CID_TYPE);
    }

    public function getSelectOptions()
    {
        $options = [];
        if ($this->getCidType() === Data::BLOCK_TYPE_SHIPPING) {
            foreach ($this->helper->getShippingDocumentTypes() as $v) {
                $options[] = ['value' => $v, 'label' => $v];
            }
        }
        return $options;
    }

    public function getNumberId()
    {
        return sprintf('%s-cid-number', $this->getCidType());
    }

    public function getNumberName()
    {
        return sprintf('order[%s]', $this->getCidType() === Data::BLOCK_TYPE_SHIPPING ? CidFieldsInterface::SHIPPING_CID_NUMBER : CidFieldsInterface::BILLING_CID_NUMBER);
    }
}
