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

namespace Mugar\CustomerIdentificationDocument\Block\Adminhtml\Button\CidType;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterface;
use Mugar\CustomerIdentificationDocument\Registry\CidType as CidTypeRegistry;

class Delete implements ButtonProviderInterface
{
    /**
     * @var CidTypeRegistry
     */
    private $registry;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * Delete constructor.
     * @param CidTypeRegistry $registry
     * @param UrlInterface $url
     */
    public function __construct(
        CidTypeRegistry $registry,
        UrlInterface $url
    ) {
        $this->registry = $registry;
        $this->url = $url;
    }

    /**
     * get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getCidTypeId()) {
            $data = [
                'label' => __('Delete Document Type'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return CidTypeInterface | null
     */
    private function getCidType()
    {
        return $this->registry->get();
    }

    /**
     * @return int|null
     */
    private function getCidTypeId()
    {
        $item = $this->getCidType();
        return ($item) ? $item->getId() : null;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->url->getUrl(
            '*/*/delete',
            [
                'cid_type_id' => $this->getCidTypeId(),
            ]
        );
    }
}
