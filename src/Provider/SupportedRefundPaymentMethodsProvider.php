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

namespace Sylius\RefundPlugin\Provider;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class SupportedRefundPaymentMethodsProvider implements RefundPaymentMethodsProviderInterface
{
    /**
     * @param PaymentMethodRepositoryInterface<PaymentMethodInterface> $paymentMethodRepository
     * @param string[] $supportedGateways
     */
    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private array $supportedGateways,
    ) {
    }

    /** @return PaymentMethodInterface[] */
    public function findForOrder(OrderInterface $order): array
    {
        /** @var ChannelInterface|null $channel */
        $channel = $order->getChannel();
        Assert::notNull($channel);

        return $this->find($channel);
    }

    /** @return PaymentMethodInterface[] */
    private function find(ChannelInterface $channel): array
    {
        return array_values(array_filter(
            $this->paymentMethodRepository->findEnabledForChannel($channel),
            function (PaymentMethodInterface $paymentMethod): bool {
                $gatewayConfig = $paymentMethod->getGatewayConfig();
                Assert::notNull($gatewayConfig);

                return in_array($gatewayConfig->getFactoryName(), $this->supportedGateways, true);
            },
        ));
    }
}
