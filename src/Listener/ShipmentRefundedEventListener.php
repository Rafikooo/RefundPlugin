<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\RefundPlugin\Listener;

use Sylius\RefundPlugin\Event\ShipmentRefunded;
use Sylius\RefundPlugin\StateResolver\OrderPartiallyRefundedStateResolverInterface;

final class ShipmentRefundedEventListener
{
    private OrderPartiallyRefundedStateResolverInterface $orderPartiallyRefundedStateResolver;

    public function __construct(OrderPartiallyRefundedStateResolverInterface $orderPartiallyRefundedStateResolver)
    {
        $this->orderPartiallyRefundedStateResolver = $orderPartiallyRefundedStateResolver;
    }

    public function __invoke(ShipmentRefunded $shipmentRefunded): void
    {
        $this->orderPartiallyRefundedStateResolver->resolve($shipmentRefunded->orderNumber());
    }
}
