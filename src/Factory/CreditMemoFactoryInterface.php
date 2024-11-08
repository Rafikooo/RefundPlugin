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

namespace Sylius\RefundPlugin\Factory;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\RefundPlugin\Entity\CreditMemoInterface;
use Sylius\RefundPlugin\Entity\CustomerBillingDataInterface;
use Sylius\RefundPlugin\Entity\LineItemInterface;
use Sylius\RefundPlugin\Entity\ShopBillingDataInterface;
use Sylius\RefundPlugin\Entity\TaxItemInterface;

interface CreditMemoFactoryInterface extends FactoryInterface
{
    /**
     * @param LineItemInterface[] $lineItems
     * @param TaxItemInterface[] $taxItems
     */
    public function createWithData(
        OrderInterface $order,
        int $total,
        array $lineItems,
        array $taxItems,
        string $comment,
        CustomerBillingDataInterface $from,
        ?ShopBillingDataInterface $to,
    ): CreditMemoInterface;
}
