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

use Doctrine\Persistence\ObjectManager;
use SM\Factory\FactoryInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Abstraction\StateMachine\WinzouStateMachineAdapter;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderPaymentTransitions;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Checker\OrderFullyRefundedTotalCheckerInterface;
use Webmozart\Assert\Assert;

final class OrderFullyRefundedStateResolver implements OrderFullyRefundedStateResolverInterface
{
    /** @param OrderRepositoryInterface<OrderInterface> $orderRepository */
    public function __construct(
        private readonly FactoryInterface|StateMachineInterface $stateMachineFactory,
        private readonly ObjectManager $orderManager,
        private readonly OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
        if ($this->stateMachineFactory instanceof FactoryInterface) {
            trigger_deprecation(
                'sylius/refund-plugin',
                '1.6',
                sprintf(
                    'Passing an instance of "%s" as the first argument is deprecated. It will accept only instances of "%s" in RefundPlugin 2.0.',
                    FactoryInterface::class,
                    StateMachineInterface::class,
                ),
            );
        }
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
            ->getStateMachine()
            ->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_REFUND)
        ;

        $this->orderManager->flush();
    }

    private function getStateMachine(): StateMachineInterface
    {
        if ($this->stateMachineFactory instanceof FactoryInterface) {
            return new WinzouStateMachineAdapter($this->stateMachineFactory);
        }

        return $this->stateMachineFactory;
    }
}
