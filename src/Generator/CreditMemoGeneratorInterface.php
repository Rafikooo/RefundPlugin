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

namespace Sylius\RefundPlugin\Generator;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\RefundPlugin\Entity\CreditMemoInterface;
use Sylius\RefundPlugin\Model\UnitRefundInterface;

interface CreditMemoGeneratorInterface
{
    /** @param UnitRefundInterface[] $units */
    public function generate(
        OrderInterface $order,
        int $total,
        array $units,
        string $comment,
    ): CreditMemoInterface;
}
