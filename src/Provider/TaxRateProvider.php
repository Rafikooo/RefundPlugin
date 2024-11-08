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
use Sylius\Component\Order\Model\AdjustableInterface;
use Sylius\RefundPlugin\Exception\MoreThanOneTaxAdjustment;
use Webmozart\Assert\Assert;

final class TaxRateProvider implements TaxRateProviderInterface
{
    public function provide(AdjustableInterface $adjustable): ?string
    {
        $taxAdjustments = $adjustable->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);

        if (count($taxAdjustments) > 1) {
            throw MoreThanOneTaxAdjustment::occur();
        }

        if ($taxAdjustments->isEmpty()) {
            return null;
        }

        /** @var AdjustmentInterface $adjustment */
        $adjustment = $taxAdjustments->first();

        $details = $adjustment->getDetails();

        Assert::keyExists(
            $details,
            'taxRateAmount',
            'There is no tax rate amount in details of this adjustment',
        );

        return $details['taxRateAmount'] * 100 . '%';
    }
}
