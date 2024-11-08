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

namespace Sylius\RefundPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface CreditMemoInterface extends ResourceInterface
{
    public function setId(string $id): void;

    public function getNumber(): ?string;

    public function setNumber(string $number): void;

    public function getOrder(): ?OrderInterface;

    public function setOrder(OrderInterface $order): void;

    public function getTotal(): int;

    public function setTotal(int $total): void;

    public function getCurrencyCode(): ?string;

    public function setCurrencyCode(string $currencyCode): void;

    public function getLocaleCode(): ?string;

    public function setLocaleCode(string $localeCode): void;

    public function getChannel(): ?ChannelInterface;

    public function setChannel(ChannelInterface $channel): void;

    /** @return Collection<int, LineItemInterface> */
    public function getLineItems(): Collection;

    /** @param Collection<int, LineItemInterface> $lineItems */
    public function setLineItems(Collection $lineItems): void;

    /** @return Collection<int, TaxItemInterface> */
    public function getTaxItems(): Collection;

    /** @param Collection<int, TaxItemInterface> $taxItems */
    public function setTaxItems(Collection $taxItems): void;

    public function getComment(): ?string;

    public function setComment(string $comment): void;

    public function getIssuedAt(): ?\DateTimeImmutable;

    public function setIssuedAt(\DateTimeImmutable $issuedAt): void;

    public function getFrom(): ?CustomerBillingDataInterface;

    public function setFrom(CustomerBillingDataInterface $from): void;

    public function getTo(): ?ShopBillingDataInterface;

    public function setTo(?ShopBillingDataInterface $to): void;

    public function getNetValueTotal(): int;

    public function getTaxTotal(): int;
}
