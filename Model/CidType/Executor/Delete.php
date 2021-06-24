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

namespace Mugar\CustomerIdentificationDocument\Model\CidType\Executor;

use Mugar\CustomerIdentificationDocument\Api\CidTypesRepositoryInterface;
use Mugar\CustomerIdentificationDocument\Api\ExecutorInterface;

class Delete implements ExecutorInterface
{
    /**
     * @var CidTypesRepositoryInterface
     */
    private $cidTypeRepository;

    /**
     * Delete constructor.
     * @param CidTypesRepositoryInterface $cidTypeRepository
     */
    public function __construct(
        CidTypesRepositoryInterface $cidTypeRepository
    ) {
        $this->cidTypeRepository = $cidTypeRepository;
    }

    /**
     * @param int $id
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute($id)
    {
        $this->cidTypeRepository->deleteById($id);
    }
}
