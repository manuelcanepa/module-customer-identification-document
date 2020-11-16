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

namespace Mugar\CustomerIdentificationDocument\Registry;

use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterfaceFactory;

class CidType
{

    /**
     * @var CidTypeInterface
     */
    private $cidType;

    /**
     * @var CidTypeInterfaceFactory
     */
    private $cidTypeFactory;

    public function __construct(CidTypeInterfaceFactory $cidTypeFactory)
    {
        $this->cidTypeFactory = $cidTypeFactory;
    }

    public function set(CidTypeInterface $cidType): void
    {
        $this->cidType = $cidType;
    }

    public function get(): CidTypeInterface
    {
        return $this->cidType ?? $this->createNullCidType();
    }

    private function createNullCidType(): CidTypeInterface
    {
        return $this->cidTypeFactory->create();
    }

}
