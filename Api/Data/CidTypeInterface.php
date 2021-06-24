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

namespace Mugar\CustomerIdentificationDocument\Api\Data;

/**
 * @api
 */
interface CidTypeInterface
{
    const ID = 'cid_type_id';
    const NAME = 'name';
    const ACTIVE = 'active';

    /**
     * @param int $id
     * @return CidTypeInterface
     */
    public function setCidTypeId($id);

    /**
     * @return int
     */
    public function getCidTypeId();

    /**
     * @param string $name
     * @return CidTypeInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param int $active
     * @return CidTypeInterface
     */
    public function setActive($active);

    /**
     * @return bool
     */
    public function getActive();
}
