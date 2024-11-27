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

namespace Sylius\RefundPlugin\StateResolver;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderPaymentTransitions;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Checker\OrderFullyRefundedTotalCheckerInterface;
use Webmozart\Assert\Assert;

final readonly class OrderFullyRefundedStateResolver implements OrderFullyRefundedStateResolverInterface
{
    /** @param OrderRepositoryInterface<OrderInterface> $orderRepository */
    public function __construct(
        private StateMachineInterface $stateMachineFactory,
        private EntityManagerInterface $orderManager,
        private OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        private OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function resolve(string $orderNumber): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneByNumber($orderNumber);
        Assert::notNull($order);

        if (
            !$this->orderFullyRefundedTotalChecker->isOrderFullyRefunded($order) ||
            OrderPaymentStates::STATE_REFUNDED === $order->getPaymentState()
        ) {
            return;
        }

        $this
            ->stateMachineFactory
            ->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_REFUND)
        ;

        $this->orderManager->flush();
    }
}
