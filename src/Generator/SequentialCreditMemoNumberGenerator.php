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

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\RefundPlugin\Entity\CreditMemoSequenceInterface;
use Sylius\RefundPlugin\Factory\CreditMemoSequenceFactoryInterface;

final readonly class SequentialCreditMemoNumberGenerator implements CreditMemoNumberGeneratorInterface
{
    /** @param ObjectRepository<CreditMemoSequenceInterface> $sequenceRepository */
    public function __construct(
        private ObjectRepository $sequenceRepository,
        private CreditMemoSequenceFactoryInterface $sequenceFactory,
        private EntityManagerInterface $sequenceManager,
        private int $startNumber = 1,
        private int $numberLength = 9,
    ) {
    }

    public function generate(OrderInterface $order, \DateTimeInterface $issuedAt): string
    {
        $identifierPrefix = $issuedAt->format('Y/m') . '/';

        $sequence = $this->getSequence();

        $this->sequenceManager->lock($sequence, LockMode::OPTIMISTIC, $sequence->getVersion());

        $number = $this->generateNumber($sequence->getIndex());
        $sequence->incrementIndex();

        return $identifierPrefix . $number;
    }

    private function generateNumber(int $index): string
    {
        $number = $this->startNumber + $index;

        return str_pad((string) $number, $this->numberLength, '0', \STR_PAD_LEFT);
    }

    private function getSequence(): CreditMemoSequenceInterface
    {
        /** @var CreditMemoSequenceInterface|null $sequence */
        $sequence = $this->sequenceRepository->findOneBy([]);
        if (null !== $sequence) {
            return $sequence;
        }

        $sequence = $this->sequenceFactory->createNew();
        $this->sequenceManager->persist($sequence);

        return $sequence;
    }
}
