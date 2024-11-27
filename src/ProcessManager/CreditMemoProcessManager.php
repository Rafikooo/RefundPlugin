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

namespace Sylius\RefundPlugin\ProcessManager;

use Sylius\RefundPlugin\Command\GenerateCreditMemo;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CreditMemoProcessManager implements UnitsRefundedProcessStepInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function next(UnitsRefunded $unitsRefunded): void
    {
        $this->commandBus->dispatch(new GenerateCreditMemo(
            $unitsRefunded->orderNumber(),
            $unitsRefunded->amount(),
            $unitsRefunded->units(),
            $unitsRefunded->comment(),
        ));
    }
}
