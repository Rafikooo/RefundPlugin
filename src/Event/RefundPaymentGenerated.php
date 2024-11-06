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

class RefundPaymentGenerated
{
    public function __construct(
        private readonly int $id,
        private readonly string $orderNumber,
        private readonly int $amount,
        private readonly string $currencyCode,
        private readonly int $paymentMethodId,
        private readonly int $paymentId,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currencyCode(): string
    {
        return $this->currencyCode;
    }

    public function paymentMethodId(): int
    {
        return $this->paymentMethodId;
    }

    public function paymentId(): int
    {
        return $this->paymentId;
    }
}
