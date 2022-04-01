<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model;

/**
 * Class FormRecord
 * @package Alekseon\CustomFormsBuilder\Model
 */
class FormRecord extends \Alekseon\AlekseonEav\Model\Entity
{
    /**
     * @var EmailNotification
     */
    protected $emailNotification;
    /**
     * @var FormRepository
     */
    protected $formRepository;

    /**
     * FormRecord constructor.
     * @param \Alekseon\AlekseonEav\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\FormRecord $resource
     * @param ResourceModel\FormRecord\Collection $resourceCollection
     * @param EmailNotification $emailNotification
     */
    public function __construct(
        \Alekseon\AlekseonEav\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Collection $resourceCollection,
        \Alekseon\CustomFormsBuilder\Model\EmailNotification $emailNotification,
        \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
    ) {
        $this->emailNotification  =$emailNotification;
        $this->formRepository = $formRepository;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getForm()
    {
        return $this->formRepository->getById($this->getFormId());
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    public function afterSave()
    {
        if ($this->isObjectNew() && $this->getForm()->getEnableEmailNotification()) {
            $this->emailNotification->sendNotificationEmail(
                [
                    'form' => $this->getForm(),
                    'entity' => $this,
                ]
            );
        }
        parent::afterSave();
    }
}
