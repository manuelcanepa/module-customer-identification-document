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

namespace Mugar\CustomerIdentificationDocument\Block\Adminhtml\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Save implements ButtonProviderInterface
{
    /**
     * @var string | null
     */
    private $label;

    /**
     * Save constructor.
     * @param string $label
     */
    public function __construct(
        $label = null
    ) {
        $this->label = $label;
    }

    /**
     * get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => $this->getLabel(),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }

    /**
     * @return \Magento\Framework\Phrase|null|string
     */
    private function getLabel()
    {
        if ($this->label === null) {
            return __('Save');
        }
        return $this->label;
    }
}
