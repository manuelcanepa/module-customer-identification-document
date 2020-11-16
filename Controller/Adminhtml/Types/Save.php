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
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Mugar\CustomerIdentificationDocument\Api\CidTypesRepositoryInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterfaceFactory;
use Mugar\CustomerIdentificationDocument\Registry\CidType as CidTypeRegistry;

/**
 * Class Save
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends Action
{
    /**
     * cid type factory
     * @var CidTypeInterfaceFactory
     */
    protected $cidTypeInterfaceFactory;

    /**
     * Data Object Processor
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * Data Object Helper
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * Data Persistor
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var CidTypeRegistry
     */
    protected $registry;

    /**
     * Cid type repository
     * @var CidTypesRepositoryInterface
     */
    protected $cidTypeRepository;

    /**
     * Save constructor.
     * @param Context $context
     * @param CidTypeInterfaceFactory $cidTypeInterfaceFactory
     * @param CidTypesRepositoryInterface $cidTypeRepository
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     * @param CidTypeRegistry $registry
     */
    public function __construct(
        Context $context,
        CidTypeInterfaceFactory $cidTypeInterfaceFactory,
        CidTypesRepositoryInterface $cidTypeRepository,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor,
        CidTypeRegistry $registry
    ) {
        $this->cidTypeInterfaceFactory = $cidTypeInterfaceFactory;
        $this->cidTypeRepository = $cidTypeRepository;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPersistor = $dataPersistor;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * run the action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var CidTypeInterface $cidType */
        $cidType = null;
        $postData = $this->getRequest()->getPostValue();
        $data = $postData;
        $id = !empty($data['cid_type_id']) ? $data['cid_type_id'] : null;
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if ($id) {
                $cidType = $this->cidTypeRepository->get((int) $id);
            } else {
                unset($data['cid_type_id']);
                $cidType = $this->cidTypeInterfaceFactory->create();
            }
            $this->dataObjectHelper->populateWithArray($cidType, $data, CidTypeInterface::class);
            $this->cidTypeRepository->save($cidType);
            $this->messageManager->addSuccessMessage(__('You saved the document type'));
            $this->dataPersistor->clear('cid_type');
            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('*/*/edit', ['cid_type_id' => $cidType->getId()]);
            } else {
                $resultRedirect->setPath('*/*');
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('cid_type', $postData);
            $resultRedirect->setPath('*/*/edit', ['cid_type_id' => $id]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the document type'));
            $this->dataPersistor->set('cid_type', $postData);
            $resultRedirect->setPath('*/*/edit', ['cid_type_id' => $id]);
        }
        return $resultRedirect;
    }
}
