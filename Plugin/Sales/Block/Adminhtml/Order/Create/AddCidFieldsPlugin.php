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

namespace Mugar\CustomerIdentificationDocument\Plugin\Sales\Block\Adminhtml\Order\Create;

use Magento\Sales\Block\Adminhtml\Order\Create\Data;
use Mugar\CustomerIdentificationDocument\Block\Adminhtml\Order\Create;
use Mugar\CustomerIdentificationDocument\Helper\Data as CidHelper;

class AddCidFieldsPlugin
{
    private CidHelper $helper;

    public function __construct(
        CidHelper $helper
    ) {
        $this->helper = $helper;
    }

    public function afterGetChildHtml(
        Data $subject,
        $html,
        $id,
        $useCache = true
    ) {
        if ($id === 'billing_address' && $this->helper->isBillingEnabled()) {
            /** @var Create $block */
            $block = $subject->getChildHtml('billingcidfields');
            $html = $html . $block;
        }
        if ($id === 'shipping_address' && $this->helper->isShippingEnabled()) {
            /** @var Create $block */
            $block = $subject->getChildHtml('shippingcidfields');
            $html = $html . $block;
        }

        return $html;
    }
}
