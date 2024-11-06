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

namespace Sylius\RefundPlugin\Event;

class ShipmentRefunded implements UnitRefundedInterface
{
    public function __construct(
        private readonly string $orderNumber,
        private readonly int $shipmentUnitId,
        private readonly int $amount,
    ) {
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }

    public function shipmentUnitId(): int
    {
        return $this->shipmentUnitId;
    }

    public function amount(): int
    {
        return $this->amount;
    }
}
