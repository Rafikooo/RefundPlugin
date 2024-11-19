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

use Doctrine\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use SM\Factory\FactoryInterface;
use SM\StateMachine\StateMachineInterface as WinzouStateMachineInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderPaymentTransitions;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Exception\OrderNotFound;

final class OrderPartiallyRefundedStateResolverSpec extends ObjectBehavior
{
    function let(
        OrderRepositoryInterface $orderRepository,
        FactoryInterface $stateMachineFactory,
        ObjectManager $orderManager,
    ): void {
        $this->beConstructedWith($orderRepository, $stateMachineFactory, $orderManager);
    }

    function it_marks_order_as_partially_refunded(
        OrderRepositoryInterface $orderRepository,
        StateMachineInterface $stateMachine,
        ObjectManager $orderManager,
        OrderInterface $order,
    ): void {
        $this->beConstructedWith($orderRepository, $stateMachine, $orderManager);

        $orderRepository->findOneByNumber('000777')->willReturn($order);

        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PAID);

        $stateMachine
            ->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_PARTIALLY_REFUND)
            ->shouldBeCalled()
        ;

        $orderManager->flush()->shouldBeCalled();

        $this->resolve('000777');
    }

    function it_does_nothing_if_order_is_already_marked_as_partially_refunded(
        OrderRepositoryInterface $orderRepository,
        StateMachineInterface $stateMachine,
        ObjectManager $orderManager,
        OrderInterface $order,
    ): void {
        $this->beConstructedWith($orderRepository, $stateMachine, $orderManager);

        $orderRepository->findOneByNumber('000777')->willReturn($order);

        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PARTIALLY_REFUNDED);

        $stateMachine
            ->apply($order, OrderPaymentTransitions::GRAPH, OrderPaymentTransitions::TRANSITION_PARTIALLY_REFUND)
            ->shouldNotBeCalled()
        ;

        $this->resolve('000777');
    }

    function it_throws_exception_if_there_is_no_order_with_given_number(
        OrderRepositoryInterface $orderRepository,
        StateMachineInterface $stateMachine,
        ObjectManager $orderManager,
    ): void {
        $this->beConstructedWith($orderRepository, $stateMachine, $orderManager);

        $orderRepository->findOneByNumber('000777')->willReturn(null);

        $this
            ->shouldThrow(OrderNotFound::withNumber('000777'))
            ->during('resolve', ['000777'])
        ;
    }

    function it_uses_winzou_state_machine_if_abstraction_not_passed_to_mark_order_as_partially_refunded(
        OrderRepositoryInterface $orderRepository,
        FactoryInterface $stateMachineFactory,
        ObjectManager $orderManager,
        OrderInterface $order,
        WinzouStateMachineInterface $stateMachine,
    ): void {
        $orderRepository->findOneByNumber('000777')->willReturn($order);

        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PAID);

        $stateMachineFactory->get($order, OrderPaymentTransitions::GRAPH)->willReturn($stateMachine);
        $stateMachine->apply(OrderPaymentTransitions::TRANSITION_PARTIALLY_REFUND)->shouldBeCalled();

        $orderManager->flush()->shouldBeCalled();

        $this->resolve('000777');
    }
}
