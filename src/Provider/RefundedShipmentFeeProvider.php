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

namespace Sylius\RefundPlugin\Provider;

use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class RefundedShipmentFeeProvider implements RefundedShipmentFeeProviderInterface
{
    public function __construct(private RepositoryInterface $adjustmentRepository)
    {
    }

    public function getFeeOfShipment(int $adjustmentId): int
    {
        /** @var AdjustmentInterface $adjustment */
        $adjustment = $this->adjustmentRepository->find($adjustmentId);
        Assert::notNull($adjustment);
        Assert::same($adjustment->getType(), AdjustmentInterface::SHIPPING_ADJUSTMENT);

        return $adjustment->getAmount();
    }
}
