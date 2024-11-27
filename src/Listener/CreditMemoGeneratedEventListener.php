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

namespace Sylius\RefundPlugin\Listener;

use Sylius\RefundPlugin\Command\SendCreditMemo;
use Sylius\RefundPlugin\Event\CreditMemoGenerated;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CreditMemoGeneratedEventListener
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function __invoke(CreditMemoGenerated $event): void
    {
        $this->commandBus->dispatch(new SendCreditMemo($event->number()));
    }
}
