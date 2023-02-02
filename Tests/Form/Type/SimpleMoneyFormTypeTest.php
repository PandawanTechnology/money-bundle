<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Tests\Form\Type;

use PandawanTechnology\Money\Factory\MoneyFactory;
use PandawanTechnology\Money\Manager\CurrencyManager;
use PandawanTechnology\Money\Model\Money;
use PandawanTechnology\MoneyBundle\Form\Type\AmountFormType;
use PandawanTechnology\MoneyBundle\Form\Type\SimpleMoneyFormType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class SimpleMoneyFormTypeTest extends TypeTestCase
{
    private const DEFAULT_CURRENCY_CODE = 'EUR';
    private $currencyManager;
    private $moneyFactory;

    protected function setUp(): void
    {
        $this->currencyManager = $this->createMock(CurrencyManager::class);
        $this->moneyFactory = $this->createMock(MoneyFactory::class);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $amountFormType = new AmountFormType(static::DEFAULT_CURRENCY_CODE, $this->currencyManager);
        $simpleMoneyFormType = new SimpleMoneyFormType(
            $this->currencyManager,
            static::DEFAULT_CURRENCY_CODE,
            $this->moneyFactory
        );

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([$amountFormType, $simpleMoneyFormType], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $this->moneyFactory->expects($this->exactly(2))
            ->method('createMoney')
            ->willReturnCallback(static function ($amount, $currency) {
                return new Money($amount, $currency);
            });
        $this->currencyManager->expects($this->exactly(2))
            ->method('getAllowedCurrencyCodes')
            ->willReturn([static::DEFAULT_CURRENCY_CODE]);

        $formData = [
            'amount' => 42,
        ];
        $form = $this->factory->create(SimpleMoneyFormType::class);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        /** @var Money $submittedData */
        $submittedData = $form->getData();
        $this->assertEquals('42', $submittedData->getAmount());
        $this->assertEquals(static::DEFAULT_CURRENCY_CODE, $submittedData->getCurrency());
    }
}
