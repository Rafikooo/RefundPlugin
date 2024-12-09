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

namespace Sylius\RefundPlugin\Twig\Component;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\RefundPlugin\Entity\CreditMemoInterface;
use Sylius\RefundPlugin\Repository\CreditMemoRepositoryInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent]
class OrderCreditMemosComponent
{
    public ?OrderInterface $order = null;

    public function __construct(private readonly CreditMemoRepositoryInterface $creditMemoRepository)
    {
    }

    /** @return array<CreditMemoInterface> */
    #[ExposeInTemplate(name: 'credit_memos')]
    public function getCreditMemosForOrder(): array
    {
        if ($this->order === null || $this->order->getId() === null) {
            return [];
        }

        return $this->creditMemoRepository->findByOrderId($this->order->getId());
    }
}
