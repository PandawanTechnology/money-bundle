<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Tests\Form\Type;

use PandawanTechnology\Money\Factory\MoneyFactory;
use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\Money\Model\Money;
use PandawanTechnology\MoneyBundle\Form\Type\AmountFormType;
use PandawanTechnology\MoneyBundle\Form\Type\CurrencyChoiceFormType;
use PandawanTechnology\MoneyBundle\Form\Type\MoneyFormType;
use PandawanTechnology\MoneyBundle\Formatter\CurrencyFormatter;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class MoneyFormTypeTest extends TypeTestCase
{
    private const DEFAULT_CURRENCY_CODE = 'ZBG';
    private const SUBMITTED_CURRENCY_CODE = 'EUR';

    private MockObject|CurrencyManager $currencyManager;
    private MockObject|CurrencyFormatter $currencyFormatter;
    private MockObject|MoneyFactory $moneyFactory;

    protected function setUp(): void
    {
        $this->currencyManager = $this->createMock(CurrencyManager::class);
        $this->currencyFormatter = $this->createMock(CurrencyFormatter::class);
        $this->moneyFactory = $this->createMock(MoneyFactory::class);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $currencyChoiceFormType = new CurrencyChoiceFormType(
            $this->currencyManager,
            $this->currencyFormatter,
        );
        $amountFormType = new AmountFormType(static::DEFAULT_CURRENCY_CODE, $this->currencyManager);
        $moneyFormType = new MoneyFormType($this->moneyFactory);

        return [
            new PreloadedExtension([
                $currencyChoiceFormType,
                $amountFormType,
                $moneyFormType,
            ], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $output = new Money(42, static::SUBMITTED_CURRENCY_CODE);
        $this->currencyManager->expects($this->exactly(2))
            ->method('getAllowedCurrencyCodes')
            ->willReturn([
                static::DEFAULT_CURRENCY_CODE,
                static::SUBMITTED_CURRENCY_CODE,
            ]);
        $this->moneyFactory->expects($this->once())
            ->method('createMoney')
            ->with(
                $this->equalTo(42),
                $this->equalTo(static::SUBMITTED_CURRENCY_CODE)
            )
            ->willReturn($output);

        $form = $this->factory->create(MoneyFormType::class);
        $form->submit([
            'amount' => 42,
            'currency' => static::SUBMITTED_CURRENCY_CODE,
        ]);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($output, $form->getData());
    }
}
