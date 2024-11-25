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

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMigration;

final class Version20180703112314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Regenerated Sylius Refund migrations from 1.X';
    }

    public function postUp(Schema $schema): void
    {
        $this->cleanMigrationsTable();
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('sylius_refund_credit_memo')) {
            return;
        }

        $this->addSql('CREATE TABLE sylius_refund_credit_memo (id VARCHAR(255) NOT NULL, from_id INT DEFAULT NULL, to_id INT DEFAULT NULL, channel_id INT DEFAULT NULL, order_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, total INT NOT NULL, currency_code VARCHAR(255) NOT NULL, locale_code VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, issued_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5C4F333196901F54 (number), UNIQUE INDEX UNIQ_5C4F333178CED90B (from_id), UNIQUE INDEX UNIQ_5C4F333130354A65 (to_id), INDEX IDX_5C4F333172F5A1AA (channel_id), INDEX IDX_5C4F33318D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_credit_memo_line_items (credit_memo_id VARCHAR(255) NOT NULL, line_item_id INT NOT NULL, INDEX IDX_1453B90E8E574316 (credit_memo_id), UNIQUE INDEX UNIQ_1453B90EA7CBD339 (line_item_id), PRIMARY KEY(credit_memo_id, line_item_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_credit_memo_tax_items (credit_memo_id VARCHAR(255) NOT NULL, tax_item_id INT NOT NULL, INDEX IDX_9BBDFBE28E574316 (credit_memo_id), UNIQUE INDEX UNIQ_9BBDFBE25327F254 (tax_item_id), PRIMARY KEY(credit_memo_id, tax_item_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_credit_memo_sequence (id INT AUTO_INCREMENT NOT NULL, idx INT NOT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_customer_billing_data (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, country_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, province_name VARCHAR(255) DEFAULT NULL, province_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_line_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, unit_net_price INT NOT NULL, unit_gross_price INT NOT NULL, net_value INT NOT NULL, gross_value INT NOT NULL, tax_amount INT NOT NULL, tax_rate VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_refund (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, amount INT NOT NULL, refunded_unit_id INT DEFAULT NULL, type VARCHAR(256) NOT NULL COMMENT \'(DC2Type:sylius_refund_refund_type)\', INDEX IDX_DEF86A0E8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_refund_payment (id INT AUTO_INCREMENT NOT NULL, payment_method_id INT DEFAULT NULL, order_id INT DEFAULT NULL, amount INT NOT NULL, currency_code VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_EC283EA55AA1164F (payment_method_id), INDEX IDX_EC283EA58D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_shop_billing_data (id INT AUTO_INCREMENT NOT NULL, company VARCHAR(255) DEFAULT NULL, tax_id VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_refund_tax_item (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F333178CED90B FOREIGN KEY (from_id) REFERENCES sylius_refund_customer_billing_data (id)');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F333130354A65 FOREIGN KEY (to_id) REFERENCES sylius_refund_shop_billing_data (id)');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F333172F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id)');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F33318D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90E8E574316 FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id)');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90EA7CBD339 FOREIGN KEY (line_item_id) REFERENCES sylius_refund_line_item (id)');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items ADD CONSTRAINT FK_9BBDFBE28E574316 FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id)');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items ADD CONSTRAINT FK_9BBDFBE25327F254 FOREIGN KEY (tax_item_id) REFERENCES sylius_refund_tax_item (id)');
        $this->addSql('ALTER TABLE sylius_refund_refund ADD CONSTRAINT FK_DEF86A0E8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment ADD CONSTRAINT FK_EC283EA55AA1164F FOREIGN KEY (payment_method_id) REFERENCES sylius_payment_method (id)');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment ADD CONSTRAINT FK_EC283EA58D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP FOREIGN KEY FK_5C4F333178CED90B');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP FOREIGN KEY FK_5C4F333130354A65');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP FOREIGN KEY FK_5C4F333172F5A1AA');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP FOREIGN KEY FK_5C4F33318D9F6D38');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items DROP FOREIGN KEY FK_1453B90E8E574316');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items DROP FOREIGN KEY FK_1453B90EA7CBD339');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items DROP FOREIGN KEY FK_9BBDFBE28E574316');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items DROP FOREIGN KEY FK_9BBDFBE25327F254');
        $this->addSql('ALTER TABLE sylius_refund_refund DROP FOREIGN KEY FK_DEF86A0E8D9F6D38');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment DROP FOREIGN KEY FK_EC283EA55AA1164F');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment DROP FOREIGN KEY FK_EC283EA58D9F6D38');
        $this->addSql('DROP TABLE sylius_refund_credit_memo');
        $this->addSql('DROP TABLE sylius_refund_credit_memo_line_items');
        $this->addSql('DROP TABLE sylius_refund_credit_memo_tax_items');
        $this->addSql('DROP TABLE sylius_refund_credit_memo_sequence');
        $this->addSql('DROP TABLE sylius_refund_customer_billing_data');
        $this->addSql('DROP TABLE sylius_refund_line_item');
        $this->addSql('DROP TABLE sylius_refund_refund');
        $this->addSql('DROP TABLE sylius_refund_refund_payment');
        $this->addSql('DROP TABLE sylius_refund_shop_billing_data');
        $this->addSql('DROP TABLE sylius_refund_tax_item');
    }

    private function cleanMigrationsTable(): void
    {
        $this->connection->executeStatement('DELETE FROM sylius_migrations WHERE version LIKE :version AND version NOT IN (:current)', [
            'version' => 'Sylius\\\\RefundPlugin\\\\Migrations\\\\Version%',
            'current' => [
                'Sylius\\RefundPlugin\\Migrations\\Version20241121143542',
                self::class,
            ],
        ], [
            'version' => Types::STRING,
            'current' => ArrayParameterType::STRING,
        ]);
    }
}
