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

namespace Mugar\CustomerIdentificationDocument\Controller\Adminhtml\Types;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Mugar\CustomerIdentificationDocument\Api\CidTypesRepositoryInterface;
use Mugar\CustomerIdentificationDocument\Registry\CidType as CidTypeRegistry;

class Edit extends Action
{
    /**
     * @var CidTypesRepositoryInterface
     */
    private $cidTypeRepository;

    /**
     * @var CidTypeRegistry
     */
    private $registry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param CidTypesRepositoryInterface $cidTypeRepository
     * @param CidTypeRegistry $registry
     */
    public function __construct(
        Context $context,
        CidTypesRepositoryInterface $cidTypeRepository,
        CidTypeRegistry $registry
    ) {
        $this->cidTypeRepository = $cidTypeRepository;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * get current CidType
     *
     * @return null|CidTypeInterface
     */
    private function initCidType()
    {
        $cidTypeId = $this->getRequest()->getParam('cid_type_id');
        try {
            $cidType = $this->cidTypeRepository->get($cidTypeId);
            $this->registry->set($cidType);
        } catch (NoSuchEntityException $e) {
            $cidType = null;
        }

        return $cidType;
    }

    /**
     * Edit or create document type
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $cidType = $this->initCidType();
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Mugar_CustomerIdentificationDocument::types');
        $resultPage->getConfig()->getTitle()->prepend(__('Document Types'));

        if ($cidType === null) {
            $resultPage->getConfig()->getTitle()->prepend(__('New Document Type'));
        } else {
            $resultPage->getConfig()->getTitle()->prepend($cidType->getName());
        }
        return $resultPage;
    }
}
