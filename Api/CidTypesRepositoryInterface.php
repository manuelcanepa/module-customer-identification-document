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

namespace Mugar\CustomerIdentificationDocument\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterface;

/**
 * @api
 */
interface CidTypesRepositoryInterface
{
    /**
     * @param CidTypeInterface $cidType
     * @return CidTypeInterface
     */
    public function save(CidTypeInterface $cidType);

    /**
     * @param $id
     * @return CidTypeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Mugar\CustomerIdentificationDocument\Api\Data\CidTypeSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param CidTypeInterface $cidType
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(CidTypeInterface $cidType);

    /**
     * @param int $cidTypeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($cidTypeId);

    /**
     * clear caches instances
     * @return void
     */
    public function clear();
}
