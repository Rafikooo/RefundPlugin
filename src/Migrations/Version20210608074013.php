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

namespace Sylius\RefundPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMigration;

final class Version20210608074013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change order_number to order_id on sylius_refund_refund table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_refund_refund ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_refund_refund ADD CONSTRAINT FK_DEF86A0E8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('CREATE INDEX IDX_DEF86A0E8D9F6D38 ON sylius_refund_refund (order_id)');

        $this->addSql(
            'UPDATE sylius_refund_refund
            SET sylius_refund_refund.order_id = (
                SELECT sylius_order.id FROM sylius_order WHERE sylius_order.number = sylius_refund_refund.order_number
            );',
        );

        $this->addSql('ALTER TABLE sylius_refund_refund DROP order_number');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_refund_refund DROP FOREIGN KEY FK_DEF86A0E8D9F6D38');
        $this->addSql('DROP INDEX IDX_DEF86A0E8D9F6D38 ON sylius_refund_refund');
        $this->addSql('ALTER TABLE sylius_refund_refund ADD order_number VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');

        $this->addSql(
            'UPDATE sylius_refund_refund
            SET sylius_refund_refund.order_number = (
                SELECT sylius_order.number FROM sylius_order WHERE sylius_order.id = sylius_refund_refund.order_id
            );',
        );

        $this->addSql('ALTER TABLE sylius_refund_refund DROP order_id');
    }
}
