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

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\RefundPlugin\Model\RefundTypeInterface;

class Refund implements RefundInterface
{
    protected ?int $id = null;

    public function __construct(protected OrderInterface $order, protected int $amount, protected int $refundedUnitId, protected RefundTypeInterface $type)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getRefundedUnitId(): int
    {
        return $this->refundedUnitId;
    }

    public function getType(): RefundTypeInterface
    {
        return $this->type;
    }
}
