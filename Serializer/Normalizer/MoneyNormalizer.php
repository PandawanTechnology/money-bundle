<?php

declare(strict_types=1);

namespace PandawanTechnology\MoneyBundle\Serializer\Normalizer;

use PandawanTechnology\Money\Formatter\FormatterInterface;
use PandawanTechnology\Money\Model\Money;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MoneyNormalizer implements NormalizerInterface
{
    public function __construct(private FormatterInterface $formatter)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Money;
    }

    /**
     * @param Money $object
     *
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'amount' => $this->formatter->asFloat($object),
            'currency' => $object->getCurrency(),
        ];
    }
}