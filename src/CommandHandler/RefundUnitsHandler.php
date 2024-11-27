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

namespace Sylius\RefundPlugin\CommandHandler;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Command\RefundUnits;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Sylius\RefundPlugin\Model\OrderItemUnitRefund;
use Sylius\RefundPlugin\Model\ShipmentRefund;
use Sylius\RefundPlugin\Model\UnitRefundInterface;
use Sylius\RefundPlugin\Refunder\RefunderInterface;
use Sylius\RefundPlugin\Validator\RefundUnitsCommandValidatorInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class RefundUnitsHandler
{
    private ?RefunderInterface $orderUnitsRefunder = null;

    private ?RefunderInterface $orderShipmentsRefunder = null;

    /**
     * @param iterable<RefunderInterface> $refunders
     * @param OrderRepositoryInterface<OrderInterface>|MessageBusInterface $orderRepository
     * @param RefundUnitsCommandValidatorInterface|OrderRepositoryInterface<OrderInterface> $refundUnitsCommandValidator
     */
    public function __construct(
        private readonly iterable $refunders,
        private readonly MessageBusInterface|RefunderInterface $eventBus,
        private readonly OrderRepositoryInterface|MessageBusInterface $orderRepository,
        private readonly RefundUnitsCommandValidatorInterface|OrderRepositoryInterface $refundUnitsCommandValidator,
    ) {
    }

    public function __invoke(RefundUnits $command): void
    {
        Assert::isInstanceOf($this->refundUnitsCommandValidator, RefundUnitsCommandValidatorInterface::class);
        Assert::isInstanceOf($this->orderRepository, OrderRepositoryInterface::class);
        Assert::isInstanceOf($this->eventBus, MessageBusInterface::class);

        $this->refundUnitsCommandValidator->validate($command);

        $orderNumber = $command->orderNumber();

        $refundedTotal = 0;

        $units = $command->units();

        if (null !== $this->orderUnitsRefunder && null !== $this->orderShipmentsRefunder) {
            $refundedTotal += $this->orderUnitsRefunder->refundFromOrder(array_values(array_filter($units, fn (UnitRefundInterface $unitRefund) => $unitRefund instanceof OrderItemUnitRefund)), $orderNumber);
            $refundedTotal += $this->orderShipmentsRefunder->refundFromOrder(array_values(array_filter($units, fn (UnitRefundInterface $unitRefund) => $unitRefund instanceof ShipmentRefund)), $orderNumber);
        } else {
            Assert::isIterable($this->refunders);

            foreach ($this->refunders as $refunder) {
                Assert::isInstanceOf($refunder, RefunderInterface::class);

                $refundedTotal += $refunder->refundFromOrder($units, $orderNumber);
            }
        }

        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneByNumber($orderNumber);

        /** @var string|null $currencyCode */
        $currencyCode = $order->getCurrencyCode();
        Assert::notNull($currencyCode);

        $this->eventBus->dispatch(new UnitsRefunded(
            $orderNumber,
            $units,
            $command->paymentMethodId(),
            $refundedTotal,
            $currencyCode,
            $command->comment(),
        ));
    }
}
