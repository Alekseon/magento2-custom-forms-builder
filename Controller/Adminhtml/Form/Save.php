<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form;

use Alekseon\CustomFormsBuilder\Model\Form;
use Alekseon\CustomFormsBuilder\Model\FormTab;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Save
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
 */
class Save extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form implements HttpPostActionInterface
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $returnToEdit = false;
        if ($this->getRequest()->getParam('back', false)) {
            $returnToEdit = true;
        }

        $form = false;
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $form = $this->initForm();
            $form->addData($data);
            $this->processTabs($form, $data);

            try {
                $form->getResource()->save($form);
                $this->messageManager->addSuccessMessage(__('You saved the form.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $returnToEdit = true;
            }
        }

        if ($returnToEdit && $form) {
            return $this->returnResult('*/*/edit', ['_current' => true, 'entity_id' => $form->getId()]);
        } else {
            if ($this->getRequest()->getParam('back_to_records')) {
                return $this->returnResult('*/formRecord/index', ['id' => $form->getId()]);
            } else {
                return $this->returnResult('*/*/');
            }
        }
    }

    /**
     * @param Form $form
     * @param array $data
     * @return void
     */
    private function processTabs(Form $form, array $data = [])
    {
        if (isset($data['form_tabs'])) {
            $fomTabs = $form->getFormTabs();
            /** @var FormTab $tab */
            foreach ($fomTabs as $tab) {
                if ($tab->getId() && !isset($data['form_tabs'][$tab->getId()])) {
                    $tab->setDeleted(true);
                }
            }
            foreach ($data['form_tabs'] as $tabId => $tabData) {
                /** @var FormTab $tab */
                $tab = $fomTabs[$tabId] ?? false;
                if ($tab) {
                    $tab->addData($tabData);
                } else {
                    $tabData['tmp_tab_id'] = $tabId;
                    $form->addFormTab($tabData);
                }
            }
        }
    }
}
