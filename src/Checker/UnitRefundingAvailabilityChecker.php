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

namespace Sylius\RefundPlugin\Checker;

use Sylius\RefundPlugin\Model\RefundTypeInterface;
use Sylius\RefundPlugin\Provider\RemainingTotalProviderInterface;

final readonly class UnitRefundingAvailabilityChecker implements UnitRefundingAvailabilityCheckerInterface
{
    public function __construct(private RemainingTotalProviderInterface $remainingTotalProvider)
    {
    }

    public function __invoke(int $unitId, RefundTypeInterface $refundType): bool
    {
        return $this->remainingTotalProvider->getTotalLeftToRefund($unitId, $refundType) > 0;
    }
}
