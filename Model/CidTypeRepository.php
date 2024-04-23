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

namespace Mugar\CustomerIdentificationDocument\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Model\AbstractModel;
use Mugar\CustomerIdentificationDocument\Api\CidTypesRepositoryInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterfaceFactory;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeSearchResultInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeSearchResultInterfaceFactory;
use Mugar\CustomerIdentificationDocument\Model\ResourceModel\CidType as CidTypeResourceModel;
use Mugar\CustomerIdentificationDocument\Model\ResourceModel\CidType\Collection;
use Mugar\CustomerIdentificationDocument\Model\ResourceModel\CidType\CollectionFactory;

class CidTypeRepository implements CidTypesRepositoryInterface
{
    /**
     * Cached instances
     *
     * @var array
     */
    protected $instances = [];

    /**
     * Cid type resource model
     *
     * @var CidTypeResourceModel
     */
    protected $resource;

    /**
     * Cid type collection factory
     *
     * @var CollectionFactory
     */
    protected $cidTypeCollectionFactory;

    /**
     * Cid type interface factory
     *
     * @var CidTypeInterfaceFactory
     */
    protected $cidTypeInterfaceFactory;

    /**
     * Data Object Helper
     *
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * Search result factory
     *
     * @var CidTypeSearchResultInterface
     */
    protected $searchResultsFactory;
    protected $collectionFactory;

    /**
     * constructor
     * @param CidTypeResourceModel $resource
     * @param CollectionFactory $collectionFactory
     * @param CidTypeInterfaceFactory $cidTypeInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param CidTypeSearchResultInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        CidTypeResourceModel $resource,
        CollectionFactory $collectionFactory,
        CidTypeInterfaceFactory $cidTypeInterfaceFactory,
        DataObjectHelper $dataObjectHelper,
        CidTypeSearchResultInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->cidTypeInterfaceFactory = $cidTypeInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Save cid type.
     *
     * @param CidTypeInterface $cidType
     * @return CidTypeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(CidTypeInterface $cidType)
    {
        /** @var CidTypeInterface|\Magento\Framework\Model\AbstractModel $cidType */
        try {
            $this->resource->save($cidType);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the document type: %1',
                $exception->getMessage()
            ));
        }
        return $cidType;
    }

    /**
     * Retrieve document type
     *
     * @param int $cidTypeId
     * @return CidTypeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($cidTypeId)
    {
        if (!isset($this->instances[$cidTypeId])) {
            /** @var CidTypeInterface|\Magento\Framework\Model\AbstractModel $cidType */
            $cidType = $this->cidTypeInterfaceFactory->create();
            $this->resource->load($cidType, $cidTypeId);
            if (!$cidType->getId()) {
                throw new NoSuchEntityException(__('Requested document type doesn\'t exist'));
            }
            $this->instances[$cidTypeId] = $cidType;
        }
        return $this->instances[$cidTypeId];
    }

    /**
     * Retrieve document types matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CidTypeSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var CidTypeSearchResultInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var \Magento\Framework\Api\Search\FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? SortOrder::SORT_ASC : SortOrder::SORT_DESC
                );
            }
        } else {
            $collection->addOrder('main_table.' . CidTypeInterface::ID, SortOrder::SORT_ASC);
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        /** @var CidTypeInterface[] $cidTypes */
        $cidTypes = [];
        foreach ($collection as $cidType) {
            /** @var CidTypeInterface $cidTypeDataObject */
            $cidTypeDataObject = $this->cidTypeInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $cidTypeDataObject,
                $cidType->getData(),
                CidTypeInterface::class
            );
            $cidTypes[] = $cidTypeDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($cidTypes);
    }

    /**
     * Delete document type
     *
     * @param CidTypeInterface $cidType
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(CidTypeInterface $cidType)
    {
        /** @var CidTypeInterface|AbstractModel $cidType */
        $id = $cidType->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($cidType);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove document type %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete document type by ID.
     *
     * @param int $cidTypeId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($cidTypeId)
    {
        $cidType = $this->get($cidTypeId);
        return $this->delete($cidType);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return $this
     * @throws InputException
     */
    protected function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        Collection $collection
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }

    /**
     * clear caches instances
     * @return void
     */
    public function clear()
    {
        $this->instances = [];
    }
}
