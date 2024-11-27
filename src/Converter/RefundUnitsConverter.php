<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\RefundPlugin\Converter;

use Sylius\RefundPlugin\Calculator\UnitRefundTotalCalculatorInterface;
use Sylius\RefundPlugin\Model\UnitRefundInterface;
use Webmozart\Assert\Assert;

final readonly class RefundUnitsConverter implements RefundUnitsConverterInterface
{
    public function __construct(private UnitRefundTotalCalculatorInterface $unitRefundTotalCalculator)
    {
    }

    /**
     * @param UnitRefundInterface[] $units
     *
     * @return UnitRefundInterface[]
     */
    public function convert(array $units, string $unitRefundClass): array
    {
        if (!class_exists($unitRefundClass) || !is_subclass_of($unitRefundClass, UnitRefundInterface::class)) {
            throw new \InvalidArgumentException(sprintf('The class "%s" must implement "%s".', $unitRefundClass, UnitRefundInterface::class));
        }

        $units = $this->filterEmptyRefundUnits($units);
        $refundUnits = [];

        foreach ($units as $id => $unit) {
            // Calculate the refund total using the refund type class provided.
            $total = $this
                ->unitRefundTotalCalculator
                ->calculateForUnitWithIdAndType(
                    $id,
                    $unitRefundClass::type(), // Get the type from the refund class
                    /** @phpstan-ignore-next-line  */
                    $this->getAmount($unit),
                );

            // Create the unit refund object
            $unitRefund = new $unitRefundClass((int) $id, $total);
            Assert::isInstanceOf($unitRefund, UnitRefundInterface::class);

            $refundUnits[] = $unitRefund;
        }

        return $refundUnits;
    }

    /**
     * @param UnitRefundInterface[] $units
     *
     * @return UnitRefundInterface[]
     */
    private function filterEmptyRefundUnits(array $units): array
    {
        /** @phpstan-ignore-next-line  */
        return array_filter($units, fn (array $refundUnit): bool => (isset($refundUnit['amount']) && $refundUnit['amount'] !== '') || isset($refundUnit['full']));
    }

    /** @param UnitRefundInterface[] $unit */
    private function getAmount(array $unit): ?float
    {
        if (isset($unit['full'])) {
            return null;
        }

        Assert::keyExists($unit, 'amount');

        /** @phpstan-ignore-next-line  */
        return (float) $unit['amount'];
    }
}
