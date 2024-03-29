<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Test\Unit\Model;

use Alekseon\CustomFormsBuilder\Model\Form;
use Alekseon\CustomFormsBuilder\Model\FormRepository;

/**
 * Class FormRepositoryTest
 * @package Alekseon\CustomFormsBuilder\Test\Unit\Model
 */
class FormRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Alekseon\CustomFormBuilder\Model\Form
     */
    protected $form;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Alekseon\CustomFormBuilder\Model\ResourceModel\Form
     */
    protected $formResource;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Alekseon\CustomFormBuilder\Model\FormRepository
     */
    protected $formRepository;

    /**
     * Initialize repository
     */
    protected function setUp(): void
    {

        $formFactory = $this->getMockBuilder(\Alekseon\CustomFormsBuilder\Model\FormFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->formResource = $this->getMockBuilder(\Alekseon\CustomFormsBuilder\Model\ResourceModel\Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->form = $this->getMockBuilder(\Alekseon\CustomFormsBuilder\Model\Form::class)->disableOriginalConstructor()->getMock();

        $formFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->form);

        $this->formRepository = new FormRepository(
            $formFactory
        );
    }

    /**
     * @test
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetByIdException()
    {
        $formId = '123';

        $this->form->expects($this->once())
            ->method('getId')
            ->willReturn(false);
        $this->formResource->expects($this->once())
            ->method('load')
            ->with($this->form, $formId)
            ->willReturn($this->form);
        $this->form->expects($this->once())
            ->method('getResource')
            ->willReturn($this->formResource);
        $this->formRepository->getById($formId);
    }

}
