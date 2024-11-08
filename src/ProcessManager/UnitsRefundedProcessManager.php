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

use Sylius\RefundPlugin\Event\UnitsRefunded;

final class UnitsRefundedProcessManager implements UnitsRefundedProcessManagerInterface
{
    /** @param UnitsRefundedProcessStepInterface[] $steps */
    public function __construct(private readonly iterable $steps)
    {
    }

    public function __invoke(UnitsRefunded $event): void
    {
        /** @var UnitsRefundedProcessStepInterface $step */
        foreach ($this->steps as $step) {
            $step->next($event);
        }
    }
}
