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

namespace Mugar\CustomerIdentificationDocument\Model\Config\Source;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Mugar\CustomerIdentificationDocument\Api\CidTypesRepositoryInterface;

class Types implements OptionSourceInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var CidTypesRepositoryInterface
     */
    protected $cidTypesRepository;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CidTypesRepositoryInterface $cidTypesRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->cidTypesRepository = $cidTypesRepository;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return array_map(function ($key, $label) {
            return ['value' => $key, 'label' => $label];
        }, array_keys($this->toArray()), $this->toArray());
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $cidTypes = [];

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('active', 1)
            ->create();

        $cidTypesRepository = $this->cidTypesRepository->getList($searchCriteria);

        foreach ($cidTypesRepository->getItems() as $cidType) {
            $cidTypes[$cidType->getId()] = $cidType->getName();
        }
        return $cidTypes;
    }
}
