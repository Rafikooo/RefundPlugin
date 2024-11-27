<?php

declare(strict_types=1);

namespace Tests\Sylius\RefundPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;

final readonly class OrderContext implements Context
{
    public function __construct(private EntityManagerInterface $orderManager)
    {
    }

    /**
     * @Given /^(this order) has been placed in ("[^"]+" channel)$/
     */
    public function orderHasBeenPlacedInChannel(OrderInterface $order, ChannelInterface $channel): void
    {
        $order->setChannel($channel);

        $this->orderManager->flush();
    }
}
