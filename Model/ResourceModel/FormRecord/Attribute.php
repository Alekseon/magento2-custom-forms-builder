<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord;

/**
 * Class Attribute
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord
 */
class Attribute extends \Alekseon\AlekseonEav\Model\ResourceModel\Attribute
{
    /**
     * @var string
     */
    protected $entityTypeCode = 'alekseon_custom_form_record';
    /**
     * @var string
     */
    protected $mainTable = 'alekseon_custom_form_record_attribute';
    /**
     * @var string
     */
    protected $backendTablePrefix = 'alekseon_custom_form_record_entity';
    /**
     * @var string
     */
    protected $attributeOptionTable = 'alekseon_custom_form_record_attribute_option';
    /**
     * @var string
     */
    protected $attributeOptionValueTable = 'alekseon_custom_form_record_attribute_option_value';
    /**
     * @var string
     */
    protected $attributeFrontendLabelsTable =  'alekseon_custom_form_record_attribute_frontend_label';
    /**
     * @var string
     */
    protected $attributeFrontendNotesTable = 'alekseon_custom_form_record_attribute_frontend_note';

    /**
     * @inheritDoc
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object) // @codingStandardsIgnoreLine
    {
        parent::_afterSave($object);
        $this->processFrontendNotes($object);
        return $this;
    }

    /**
     * @param \Alekseon\AlekseonEav\Model\Attribute $object
     * @return $this|void
     */
    private function processFrontendNotes(\Alekseon\AlekseonEav\Model\Attribute $object)
    {
        $frontendNotes = $object->getFrontendNotes();

        if (!is_array($frontendNotes)) {
            return $this;
        }

        $connection = $this->getConnection();
        $table = $this->getTable($this->attributeFrontendNotesTable);
        $currentFrontendNotes = $this->getFrontendNotes($object);

        foreach($frontendNotes as $storeId => $note) {
            if (isset($currentFrontendNotes[$storeId])) {
                $currentNote = $currentFrontendNotes[$storeId];
                if ($currentNote['note'] == $note) {
                    // note has not been changed
                } else if (!$note) {
                    // note is empty, so it can be removed
                    $connection->delete($table, ['id = ?' => $currentNote['id']]);
                } else {
                    $data = ['note' => $note];
                    $where = ['id = ?' => $currentNote['id']];
                    $connection->update($table, $data, $where);
                }
            } else {
                if ($note) {
                    $data = ['attribute_id' => (int)$object->getId(), 'store_id' => $storeId, 'note' => $note];
                    $connection->insert($table, $data);
                }
            }
        }
    }

    /**
     * @param $object
     * @param bool $asObjects
     * @return array
     */
    public function getFrontendNotes($object, $asObjects = true)
    {
        $connection = $this->getConnection();
        $frontendNotesSelect = $connection
            ->select()
            ->from($this->getTable($this->attributeFrontendNotesTable))
            ->where('attribute_id = ?', $object->getId());
        $frontendNotes = $connection->fetchAll($frontendNotesSelect);
        $result = [];
        foreach($frontendNotes as $frontendNote) {
            if ($asObjects) {
                $result[$frontendNote['store_id']] = $frontendNote;
            } else {
                $result[$frontendNote['store_id']] = $frontendNote['note'];
            }
        }
        return $result;
    }
}
