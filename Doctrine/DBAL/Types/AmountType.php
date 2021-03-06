<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PandawanTechnology\MoneyBundle\Formatter\CurrencyFormatter;

final class AmountType extends Type
{
    public const AMOUNT = 'amount';

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDecimalTypeDeclarationSQL([
            'precision' => 18,
            'scale' => CurrencyFormatter::DEFAULT_DECIMAL_SCALE,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::AMOUNT;
    }
}
