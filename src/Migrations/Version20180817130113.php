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

final class Version20180817130113 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_refund_payment (id INT AUTO_INCREMENT NOT NULL, payment_method_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, order_number VARCHAR(255) NOT NULL, amount INT NOT NULL, currency_code VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_EFA5A4B25AA1164F (payment_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_refund_payment ADD CONSTRAINT FK_EFA5A4B25AA1164F FOREIGN KEY (payment_method_id) REFERENCES sylius_payment_method (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_refund_payment');
    }
}
