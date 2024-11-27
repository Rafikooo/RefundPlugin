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
use Sylius\RefundPlugin\Entity\RefundPaymentInterface;

final readonly class RefundPaymentCompletedStateApplier implements RefundPaymentCompletedStateApplierInterface
{
    public function __construct(
        private StateMachineInterface $stateMachineFactory,
        private EntityManagerInterface $refundPaymentManager,
    ) {
    }

    public function apply(RefundPaymentInterface $refundPayment): void
    {
        $this
            ->stateMachineFactory
            ->apply($refundPayment, RefundPaymentTransitions::GRAPH, RefundPaymentTransitions::TRANSITION_COMPLETE)
        ;

        $this->refundPaymentManager->flush();
    }
}
