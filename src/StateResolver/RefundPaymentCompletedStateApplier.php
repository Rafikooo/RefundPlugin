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
use Sylius\RefundPlugin\Entity\RefundPaymentInterface;

final class RefundPaymentCompletedStateApplier implements RefundPaymentCompletedStateApplierInterface
{
    public function __construct(
        private readonly FactoryInterface|StateMachineInterface $stateMachineFactory,
        private readonly ObjectManager $refundPaymentManager,
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

    public function apply(RefundPaymentInterface $refundPayment): void
    {
        $this
            ->getStateMachine()
            ->apply($refundPayment, RefundPaymentTransitions::GRAPH, RefundPaymentTransitions::TRANSITION_COMPLETE)
        ;

        $this->refundPaymentManager->flush();
    }

    private function getStateMachine(): StateMachineInterface
    {
        if ($this->stateMachineFactory instanceof FactoryInterface) {
            return new WinzouStateMachineAdapter($this->stateMachineFactory);
        }

        return $this->stateMachineFactory;
    }
}
