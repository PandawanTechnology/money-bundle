<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Twig;

use PandawanTechnology\Money\Formatter\FormatterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MoneyExtension extends AbstractExtension
{
    public function __construct(private FormatterInterface $formatter)
    {
    }

    /**
     * @inheritDoc
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('money_format', [$this->formatter, 'formatPrice']),
            new TwigFilter('currency_symbol', [$this->formatter, 'formatCurrencySymbol']),
        ];
    }
}