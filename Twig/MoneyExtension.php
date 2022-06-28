<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Twig;

use PandawanTechnology\Money\Calculator\CalculatorInterface;
use PandawanTechnology\Money\Formatter\FormatterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MoneyExtension extends AbstractExtension
{
    public function __construct(private FormatterInterface $formatter, private CalculatorInterface $calculator)
    {
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
}
