<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Twig;

use PandawanTechnology\Money\Calculator\CalculatorInterface;
use PandawanTechnology\Money\Comparator\ComparatorInterface;
use PandawanTechnology\Money\Formatter\FormatterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

class MoneyExtension extends AbstractExtension
{
    public function __construct(
        private FormatterInterface $formatter,
        private CalculatorInterface $calculator,
        private ComparatorInterface $comparator,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('money_format', [$this->formatter, 'formatPrice']),
            new TwigFilter('currency_symbol', [$this->formatter, 'formatCurrencySymbol']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('money_divide', [$this->calculator, 'divide']),
            new TwigFunction('money_multiply', [$this->calculator, 'multiply']),
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('money_zero_amount', [$this->comparator, 'isZeroAmount']),
        ];
    }
}
