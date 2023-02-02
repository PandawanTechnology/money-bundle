<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Tests\Form\Type;

use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\MoneyBundle\Form\Type\AmountFormType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class AmountFormTypeTest extends TypeTestCase
{
    private const DEFAULT_CURRENCY_CODE = 'EUR';
    private $currencyManager;

    protected function setUp(): void
    {
        $this->currencyManager = $this->createMock(CurrencyManager::class);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new AmountFormType(static::DEFAULT_CURRENCY_CODE, $this->currencyManager);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $this->currencyManager->expects($this->once())
            ->method('getAllowedCurrencyCodes')
            ->willReturn([static::DEFAULT_CURRENCY_CODE]);

        $formData = 42;
        $form = $this->factory->create(AmountFormType::class);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals('42.00000', $form->getData());
    }
}
