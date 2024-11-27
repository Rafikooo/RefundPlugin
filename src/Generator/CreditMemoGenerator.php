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

namespace Sylius\RefundPlugin\Generator;

use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopBillingDataInterface as ChannelShopBillingData;
use Sylius\RefundPlugin\Converter\LineItem\LineItemsConverterInterface;
use Sylius\RefundPlugin\Entity\CreditMemoInterface;
use Sylius\RefundPlugin\Entity\CustomerBillingDataInterface;
use Sylius\RefundPlugin\Entity\ShopBillingDataInterface;
use Sylius\RefundPlugin\Factory\CreditMemoFactoryInterface;
use Sylius\RefundPlugin\Factory\CustomerBillingDataFactoryInterface;
use Sylius\RefundPlugin\Factory\ShopBillingDataFactoryInterface;
use Sylius\RefundPlugin\Model\OrderItemUnitRefund;
use Sylius\RefundPlugin\Model\ShipmentRefund;
use Webmozart\Assert\Assert;

final readonly class CreditMemoGenerator implements CreditMemoGeneratorInterface
{
    public function __construct(
        private LineItemsConverterInterface $lineItemsConverter,
        private TaxItemsGeneratorInterface|LineItemsConverterInterface $taxItemsGenerator,
        private CreditMemoFactoryInterface|TaxItemsGeneratorInterface $creditMemoFactory,
        private CustomerBillingDataFactoryInterface|CreditMemoFactoryInterface $customerBillingDataFactory,
        private ShopBillingDataFactoryInterface|CustomerBillingDataFactoryInterface $shopBillingDataFactory,
    ) {
    }

    /**
     * @param OrderItemUnitRefund[]|ShipmentRefund[] $units
     */
    public function generate(
        OrderInterface $order,
        int $total,
        array $units,
        string $comment,
    ): CreditMemoInterface {
        Assert::isInstanceOf($this->creditMemoFactory, CreditMemoFactoryInterface::class);
        Assert::isInstanceOf($this->taxItemsGenerator, TaxItemsGeneratorInterface::class);

        /** @var ChannelInterface|null $channel */
        $channel = $order->getChannel();
        Assert::notNull($channel);

        /** @var AddressInterface|null $billingAddress */
        $billingAddress = $order->getBillingAddress();
        Assert::notNull($billingAddress);

        $lineItems = $this->lineItemsConverter->convert($units);

        return $this->creditMemoFactory->createWithData(
            $order,
            $total,
            $lineItems,
            $this->taxItemsGenerator->generate($lineItems),
            $comment,
            $this->getFromAddress($billingAddress),
            $this->getToAddress($channel->getShopBillingData()),
        );
    }

    private function getFromAddress(AddressInterface $address): CustomerBillingDataInterface
    {
        Assert::isInstanceOf($this->customerBillingDataFactory, CustomerBillingDataFactoryInterface::class);

        return $this->customerBillingDataFactory->createWithAddress($address);
    }

    private function getToAddress(?ChannelShopBillingData $channelShopBillingData): ?ShopBillingDataInterface
    {
        Assert::isInstanceOf($this->shopBillingDataFactory, ShopBillingDataFactoryInterface::class);

        if (
            $channelShopBillingData === null ||
            ($channelShopBillingData->getStreet() === null && $channelShopBillingData->getCompany() === null)
        ) {
            return null;
        }

        return $this->shopBillingDataFactory->createWithData(
            $channelShopBillingData->getCompany(),
            $channelShopBillingData->getTaxId(),
            $channelShopBillingData->getCountryCode(),
            $channelShopBillingData->getStreet(),
            $channelShopBillingData->getCity(),
            $channelShopBillingData->getPostcode(),
        );
    }
}
