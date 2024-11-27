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

namespace spec\Sylius\RefundPlugin\StateResolver;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderPaymentTransitions;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Checker\OrderFullyRefundedTotalCheckerInterface;

final class OrderFullyRefundedStateResolverSpec extends ObjectBehavior
{
    function let(
        StateMachineInterface $stateMachineFactory,
        EntityManagerInterface $orderManager,
        OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        OrderRepositoryInterface $orderRepository,
    ): void {
        $this->beConstructedWith(
            $stateMachineFactory,
            $orderManager,
            $orderFullyRefundedTotalChecker,
            $orderRepository,
        );
    }

    function it_applies_refund_transition_on_order(
        StateMachineInterface $stateMachine,
        EntityManagerInterface $orderManager,
        OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $this->beConstructedWith($stateMachine, $orderManager, $orderFullyRefundedTotalChecker, $orderRepository);

        $orderRepository->findOneByNumber('000222')->willReturn($order);
        $orderFullyRefundedTotalChecker->isOrderFullyRefunded($order)->willReturn(true);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PAID);

        $stateMachine
            ->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_REFUND)
            ->shouldBeCalled()
        ;

        $orderManager->flush()->shouldBeCalled();

        $this->resolve('000222');
    }

    function it_does_nothing_if_order_state_is_fully_refunded(
        StateMachineInterface $stateMachine,
        EntityManagerInterface $orderManager,
        OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $this->beConstructedWith($stateMachine, $orderManager, $orderFullyRefundedTotalChecker, $orderRepository);

        $orderRepository->findOneByNumber('000222')->willReturn($order);
        $orderFullyRefundedTotalChecker->isOrderFullyRefunded($order)->willReturn(true);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_REFUNDED);

        $stateMachine
            ->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_REFUND)
            ->shouldNotBeCalled()
        ;

        $this->resolve('000222');
    }

    function it_does_nothing_if_order_is_not_fully_refunded(
        StateMachineInterface $stateMachine,
        EntityManagerInterface $orderManager,
        OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $this->beConstructedWith($stateMachine, $orderManager, $orderFullyRefundedTotalChecker, $orderRepository);

        $orderRepository->findOneByNumber('000222')->willReturn($order);
        $orderFullyRefundedTotalChecker->isOrderFullyRefunded($order)->willReturn(false);

        $stateMachine
            ->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_REFUND)
            ->shouldNotBeCalled()
        ;

        $this->resolve('000222');
    }

    function it_throws_an_exception_if_there_is_no_order_with_given_number(
        StateMachineInterface $stateMachine,
        EntityManagerInterface $orderManager,
        OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        OrderRepositoryInterface $orderRepository,
    ): void {
        $this->beConstructedWith($stateMachine, $orderManager, $orderFullyRefundedTotalChecker, $orderRepository);

        $orderRepository->findOneByNumber('000222')->willReturn(null);

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('resolve', ['000222'])
        ;
    }

    function it_uses_winzou_state_machine_if_abstraction_not_passed_to_apply_refund_transition_on_order(
        StateMachineInterface $stateMachineFactory,
        EntityManagerInterface $orderManager,
        OrderFullyRefundedTotalCheckerInterface $orderFullyRefundedTotalChecker,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order,
    ): void {
        $orderRepository->findOneByNumber('000222')->willReturn($order);
        $orderFullyRefundedTotalChecker->isOrderFullyRefunded($order)->willReturn(true);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PAID);

        $stateMachineFactory->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_REFUND)->shouldBeCalled();

        $orderManager->flush()->shouldBeCalled();

        $this->resolve('000222');
    }
}
