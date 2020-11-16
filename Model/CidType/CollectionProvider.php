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

namespace Mugar\CustomerIdentificationDocument\Model\CidType;

use Magento\Ui\Component\MassAction\Filter;
use Mugar\CustomerIdentificationDocument\Model\ResourceModel\CidType\CollectionFactory;
use Mugar\CustomerIdentificationDocument\Ui\Provider\CollectionProviderInterface;

class CollectionProvider implements CollectionProviderInterface
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * CollectionRetriever constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return \Magento\Framework\Data\Collection\AbstractDb
     */
    public function getCollection()
    {
        return $this->filter->getCollection($this->collectionFactory->create());
    }
}
