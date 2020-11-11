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

namespace Mugar\CustomerIdentificationDocument\Model\Search;

use Magento\Framework\DataObject;

/**
 * @method CidType setQuery(string $query)
 * @method string|null getQuery()
 * @method bool hasQuery()
 * @method CidType setStart(int $startPosition)
 * @method int|null getStart()
 * @method bool hasStart()
 * @method CidType setLimit(int $limit)
 * @method int|null getLimit()
 * @method bool hasLimit()
 * @method CidType setResults(array $results)
 * @method array getResults()
 * @api
 * @since 100.0.2
 */
class CidType extends DataObject
{
    /**
     * Load search results
     *
     * @return $this
     */
    public function load()
    {
        $result = [];
        $this->setResults($result);
        return $this;
    }
}
