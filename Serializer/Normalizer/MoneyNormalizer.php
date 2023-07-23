<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Serializer\Normalizer;

use PandawanTechnology\Money\Formatter\FormatterInterface;
use PandawanTechnology\Money\Model\Money;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MoneyNormalizer implements NormalizerInterface
{
    public function __construct(private FormatterInterface $formatter)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Money;
    }

    /**
     * @param Money $object
     *
     * {@inheritDoc}
     */
    public function normalize($object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return [
            'amount' => $this->formatter->asFloat($object),
            'currency' => $object->getCurrency(),
        ];
    }
}
