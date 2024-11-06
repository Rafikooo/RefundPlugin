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

namespace Sylius\RefundPlugin\Resolver;

use Gaufrette\Exception\FileNotFound;
use Sylius\RefundPlugin\Entity\CreditMemoInterface;
use Sylius\RefundPlugin\Generator\CreditMemoPdfFileGeneratorInterface;
use Sylius\RefundPlugin\Manager\CreditMemoFileManagerInterface;
use Sylius\RefundPlugin\Model\CreditMemoPdf;
use Sylius\RefundPlugin\Provider\CreditMemoFileProviderInterface;
use Sylius\RefundPlugin\Repository\CreditMemoRepositoryInterface;
use Webmozart\Assert\Assert;

final class CreditMemoFileResolver implements CreditMemoFileResolverInterface
{
    public function __construct(
        private readonly CreditMemoRepositoryInterface $creditMemoRepository,
        private readonly CreditMemoFileProviderInterface $creditMemoFileProvider,
        private readonly CreditMemoPdfFileGeneratorInterface $creditMemoPdfFileGenerator,
        private readonly CreditMemoFileManagerInterface $creditMemoFileManager,
    ) {
    }

    public function resolveByCreditMemo(CreditMemoInterface $creditMemo): CreditMemoPdf
    {
        try {
            $pdf = $this->creditMemoFileProvider->provide($creditMemo);
        } catch (FileNotFound) {
            $pdf = $this->creditMemoPdfFileGenerator->generate($creditMemo->getId());
            $this->creditMemoFileManager->save($pdf);
        }

        return $pdf;
    }

    public function resolveById(string $creditMemoId): CreditMemoPdf
    {
        /** @var CreditMemoInterface|null $creditMemo */
        $creditMemo = $this->creditMemoRepository->find($creditMemoId);
        Assert::notNull($creditMemo);

        return $this->resolveByCreditMemo($creditMemo);
    }
}
