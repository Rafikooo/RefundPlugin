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

class CreditMemoGenerated
{
    public function __construct(
        private readonly string $number,
        private readonly string $orderNumber,
    ) {
    }

    public function number(): string
    {
        return $this->number;
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }
}
