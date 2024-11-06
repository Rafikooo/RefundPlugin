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

namespace spec\Sylius\RefundPlugin\Sender;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\RefundPlugin\Entity\CreditMemoInterface;
use Sylius\RefundPlugin\Model\CreditMemoPdf;
use Sylius\RefundPlugin\Resolver\CreditMemoFilePathResolverInterface;
use Sylius\RefundPlugin\Resolver\CreditMemoFileResolverInterface;
use Sylius\RefundPlugin\Sender\CreditMemoEmailSenderInterface;

final class CreditMemoEmailSenderSpec extends ObjectBehavior
{
    function let(
        SenderInterface $sender,
        CreditMemoFileResolverInterface $creditMemoFileResolver,
        CreditMemoFilePathResolverInterface $creditMemoFilePathResolver,
    ): void {
        $this->beConstructedWith($sender, true, $creditMemoFileResolver, $creditMemoFilePathResolver);
    }

    function it_implements_credit_memo_email_sender_interface(): void
    {
        $this->shouldImplement(CreditMemoEmailSenderInterface::class);
    }

    function it_sends_an_email_with_credit_memo_and_pdf_file_attachment_to_customer(
        SenderInterface $sender,
        CreditMemoFileResolverInterface $creditMemoFileResolver,
        CreditMemoFilePathResolverInterface $creditMemoFilePathResolver,
        CreditMemoInterface $creditMemo,
    ): void {
        $creditMemoPdf = new CreditMemoPdf('credit-memo.pdf', 'Content of the credit memo');
        $creditMemoFileResolver->resolveByCreditMemo($creditMemo)->willReturn($creditMemoPdf);
        $creditMemoFilePathResolver->resolve($creditMemoPdf)->willReturn('/path/to/credit_memos/credit-memo.pdf');

        $sender
            ->send('units_refunded', ['john@example.com'], ['creditMemo' => $creditMemo], ['/path/to/credit_memos/credit-memo.pdf'])
            ->shouldBeCalled();

        $this->send($creditMemo, 'john@example.com');
    }

    function it_sends_an_email_with_credit_memo_to_customer_without_pdf_file_attachment_if_pdf_generator_is_disabled(
        SenderInterface $sender,
        CreditMemoFileResolverInterface $creditMemoFileResolver,
        CreditMemoFilePathResolverInterface $creditMemoFilePathResolver,
        CreditMemoInterface $creditMemo,
    ): void {
        $this->beConstructedWith($sender, false, $creditMemoFileResolver, $creditMemoFilePathResolver);

        $creditMemoFileResolver->resolveByCreditMemo($creditMemo)->shouldNotBeCalled();
        $creditMemoFilePathResolver->resolve()->shouldNotBeCalled();

        $sender->send('units_refunded', ['john@example.com'], ['creditMemo' => $creditMemo])->shouldBeCalled();

        $this->send($creditMemo, 'john@example.com');
    }
}
