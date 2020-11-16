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

namespace Mugar\CustomerIdentificationDocument\Ui\Provider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

interface CollectionProviderInterface
{
    /**
     * @return AbstractCollection
     */
    public function getCollection();
}
