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

namespace Sylius\RefundPlugin\Command;

use Sylius\RefundPlugin\Model\UnitRefundInterface;
use Webmozart\Assert\Assert;

class GenerateCreditMemo
{
    /**
     * @param array|UnitRefundInterface[] $units
     */
    public function __construct(
        private readonly string $orderNumber,
        private readonly int $total,
        private readonly array $units,
        private readonly string $comment,
    ) {
        Assert::allIsInstanceOf($units, UnitRefundInterface::class);
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }

    public function total(): int
    {
        return $this->total;
    }

    /** @return array|UnitRefundInterface[] */
    public function units(): array
    {
        return $this->units;
    }

    public function comment(): string
    {
        Assert::string($this->comment);

        return $this->comment;
    }
}
