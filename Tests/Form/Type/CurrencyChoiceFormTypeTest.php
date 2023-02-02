<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Tests\Form\Type;

use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\MoneyBundle\Form\Type\CurrencyChoiceFormType;
use PandawanTechnology\MoneyBundle\Formatter\CurrencyFormatter;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class CurrencyChoiceFormTypeTest extends TypeTestCase
{
    private MockObject|CurrencyManager $currencyManager;
    private MockObject|CurrencyFormatter $currencyFormatter;

    protected function setUp(): void
    {
        $this->currencyManager = $this->createMock(CurrencyManager::class);
        $this->currencyFormatter = $this->createMock(CurrencyFormatter::class);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $currencyChoiceFormType = new CurrencyChoiceFormType(
            $this->currencyManager,
            $this->currencyFormatter,
        );

        return [
            new PreloadedExtension([$currencyChoiceFormType], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $this->currencyManager->expects($this->once())
            ->method('getAllowedCurrencyCodes')
            ->willReturn(['EUR', 'USD']);

        $form = $this->factory->create(CurrencyChoiceFormType::class);
        $form->submit('EUR');

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('EUR', $form->getData());
    }
}
