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
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Mugar\CustomerIdentificationDocument\Api\CidTypesRepositoryInterface;
use Mugar\CustomerIdentificationDocument\Api\Data\CidTypeInterface;
use Mugar\CustomerIdentificationDocument\Model\CidType;
use Mugar\CustomerIdentificationDocument\Model\ResourceModel\CidType as CidTypeResourceModel;

/**
 * Class InlineEdit
 */
class InlineEdit extends Action
{
    /**
     * Cid type repository
     * @var CidTypeRepositoryInterface
     */
    protected $cidTypeRepository;

    /**
     * Data object processor
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * Data object helper
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * JSON Factory
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * Cid type resource model
     * @var CidTypeResourceModel
     */
    protected $cidTypeResourceModel;

    /**
     * constructor
     * @param Context $context
     * @param CidTypeRepositoryInterface $cidTypeRepository
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param JsonFactory $jsonFactory
     * @param CidTypeResourceModel $cidTypeResourceModel
     */
    public function __construct(
        Context $context,
        CidTypesRepositoryInterface $cidTypeRepository,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        JsonFactory $jsonFactory,
        CidTypeResourceModel $cidTypeResourceModel
    ) {
        $this->cidTypeRepository = $cidTypeRepository;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->jsonFactory = $jsonFactory;
        $this->cidTypeResourceModel = $cidTypeResourceModel;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $cidTypeId) {
            /** @var CidType|CidTypeInterface $cidType */
            try {
                $cidType = $this->cidTypeRepository->get((int) $cidTypeId);
                $cidTypeData = $postItems[$cidTypeId];
                $this->dataObjectHelper->populateWithArray($cidType, $cidTypeData, CidTypeInterface::class);
                $this->cidTypeResourceModel->saveAttribute($cidType, array_keys($cidTypeData));
            } catch (LocalizedException $e) {
                $messages[] = $this->getErrorWithCidTypeId($cidType, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithCidTypeId($cidType, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithCidTypeId(
                    $cidType,
                    __('Something went wrong while saving the document type.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
    }

    /**
     * Add cid type id to error message
     *
     * @param CidTypeInterface $cidType
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithCidTypeId(CidTypeInterface $cidType, $errorText)
    {
        return '[CID Type ID: ' . $cidType->getId() . '] ' . $errorText;
    }
}
