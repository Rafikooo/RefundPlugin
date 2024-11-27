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

namespace Sylius\RefundPlugin\Entity;

use Sylius\RefundPlugin\Exception\LineItemsCannotBeMerged;

class LineItem implements LineItemInterface
{
    protected ?int $id = null;

    public function __construct(protected string $name, protected int $quantity, protected int $unitNetPrice, protected int $unitGrossPrice, protected int $netValue, protected int $grossValue, protected int $taxAmount, protected ?string $taxRate = null)
    {
    }

    public function getId(): ?int
    {
        return $this->id();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function unitNetPrice(): int
    {
        return $this->unitNetPrice;
    }

    public function unitGrossPrice(): int
    {
        return $this->unitGrossPrice;
    }

    public function netValue(): int
    {
        return $this->netValue;
    }

    public function grossValue(): int
    {
        return $this->grossValue;
    }

    public function taxAmount(): int
    {
        return $this->taxAmount;
    }

    public function taxRate(): ?string
    {
        return $this->taxRate;
    }

    public function merge(LineItemInterface $newLineItem): void
    {
        if (!$this->compare($newLineItem)) {
            throw LineItemsCannotBeMerged::occur();
        }

        $this->quantity += $newLineItem->quantity();
        $this->netValue += $newLineItem->netValue();
        $this->grossValue += $newLineItem->grossValue();
        $this->taxAmount += $newLineItem->taxAmount();
    }

    public function compare(LineItemInterface $lineItem): bool
    {
        return
            $this->name() === $lineItem->name() &&
            $this->unitNetPrice() === $lineItem->unitNetPrice() &&
            $this->unitGrossPrice() === $lineItem->unitGrossPrice() &&
            $this->taxRate() === $lineItem->taxRate()
        ;
    }
}
