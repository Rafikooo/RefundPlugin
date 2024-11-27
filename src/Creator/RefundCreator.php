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

namespace Sylius\RefundPlugin\Creator;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Exception\UnitAlreadyRefunded;
use Sylius\RefundPlugin\Factory\RefundFactoryInterface;
use Sylius\RefundPlugin\Model\RefundTypeInterface;
use Sylius\RefundPlugin\Provider\RemainingTotalProviderInterface;
use Webmozart\Assert\Assert;

final readonly class RefundCreator implements RefundCreatorInterface
{
    /** @param OrderRepositoryInterface<OrderInterface> $orderRepository */
    public function __construct(
        private RefundFactoryInterface $refundFactory,
        private RemainingTotalProviderInterface $remainingTotalProvider,
        private OrderRepositoryInterface $orderRepository,
        private EntityManagerInterface $refundManager,
    ) {
    }

    public function __invoke(string $orderNumber, int $unitId, int $amount, RefundTypeInterface $refundType): void
    {
        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->findOneByNumber($orderNumber);
        Assert::notNull($order);

        $remainingTotal = $this->remainingTotalProvider->getTotalLeftToRefund($unitId, $refundType);

        if ($remainingTotal === 0) {
            throw UnitAlreadyRefunded::withIdAndOrderNumber($unitId, $orderNumber);
        }

        $refund = $this->refundFactory->createWithData($order, $unitId, $amount, $refundType);

        $this->refundManager->persist($refund);
        $this->refundManager->flush();
    }
}
