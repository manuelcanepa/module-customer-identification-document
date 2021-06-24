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

use Magento\Framework\Model\AbstractModel;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterface;
use Mugar\CustomerIdentificationDocument\Model\ResourceModel\CidType as CidTypeResourceModel;

class CidType extends AbstractModel implements CidTypeInterface
{
    const CACHE_TAG = 'mugar_cid_types';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mugar_cid_types';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'cid_type';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CidTypeResourceModel::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @param string $id
     * @return CidTypeInterface
     */
    public function setCidTypeId($id)
    {
        return $this->setData(CidTypeInterface::ID, $id);
    }

    /**
     * @return int
     */
    public function getCidTypeId()
    {
        return $this->getData(CidTypeInterface::ID);
    }

    /**
     * @param string $name
     * @return CidTypeInterface
     */
    public function setName($name)
    {
        return $this->setData(CidTypeInterface::NAME, $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData(CidTypeInterface::NAME);
    }

    /**
     * @param int $active
     * @return CidTypeInterface
     */
    public function setActive($active)
    {
        return $this->setData(CidTypeInterface::ACTIVE, $active);
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->getData(CidTypeInterface::ACTIVE);
    }
}
