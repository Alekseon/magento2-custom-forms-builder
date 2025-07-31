<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Field;

use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class Messages extends \Magento\Framework\View\Element\Template implements RendererInterface
{
    protected $_template = "Alekseon_CustomFormsBuilder::form/edit/field/messages.phtml";

    protected $messages = [];

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param $text
     * @return $this
     */
    public function addWarning(string $text)
    {
        return $this->addMessage($text, 'message-warning');
    }

    /**
     * @param $text
     * @return $this
     */
    public function addNotice(string $text)
    {
        return $this->addMessage($text);
    }

    /**
     * @param $text
     * @return $this
     */
    public function addError(string $text)
    {
        return $this->addMessage($text, 'message-error');
    }

    /**
     * @param string $text
     * @param string $class
     * @return $this
     */
    protected function addMessage(string $text, string $class = '')
    {
        $this->messages[] = [
            'class' => $class,
            'text' => $text,
        ];
        return $this;
    }
}
