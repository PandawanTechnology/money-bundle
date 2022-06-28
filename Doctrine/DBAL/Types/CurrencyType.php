<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class CurrencyType extends Type
{
    public const CURRENCY = 'currency';

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => 3,
            'fixed' => true,
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
        return self::CURRENCY;
    }
}
