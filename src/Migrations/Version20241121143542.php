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
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractPostgreSQLMigration;

final class Version20241121143542 extends AbstractPostgreSQLMigration
{
    public function getDescription(): string
    {
        return 'Initial migration for PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('sylius_refund_credit_memo')) {
            $this->markAsExecuted($this->getVersion());
            $this->skipIf(true, 'This migration is marked as completed.');
        }

        $this->addSql('CREATE SEQUENCE sylius_refund_credit_memo_sequence_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_refund_customer_billing_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_refund_line_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_refund_refund_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_refund_refund_payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_refund_shop_billing_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sylius_refund_tax_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sylius_refund_credit_memo (id VARCHAR(255) NOT NULL, from_id INT DEFAULT NULL, to_id INT DEFAULT NULL, channel_id INT DEFAULT NULL, order_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, total INT NOT NULL, currency_code VARCHAR(255) NOT NULL, locale_code VARCHAR(255) NOT NULL, comment TEXT NOT NULL, issued_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C4F333196901F54 ON sylius_refund_credit_memo (number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C4F333178CED90B ON sylius_refund_credit_memo (from_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C4F333130354A65 ON sylius_refund_credit_memo (to_id)');
        $this->addSql('CREATE INDEX IDX_5C4F333172F5A1AA ON sylius_refund_credit_memo (channel_id)');
        $this->addSql('CREATE INDEX IDX_5C4F33318D9F6D38 ON sylius_refund_credit_memo (order_id)');
        $this->addSql('COMMENT ON COLUMN sylius_refund_credit_memo.issued_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sylius_refund_credit_memo_line_items (credit_memo_id VARCHAR(255) NOT NULL, line_item_id INT NOT NULL, PRIMARY KEY(credit_memo_id, line_item_id))');
        $this->addSql('CREATE INDEX IDX_1453B90E8E574316 ON sylius_refund_credit_memo_line_items (credit_memo_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1453B90EA7CBD339 ON sylius_refund_credit_memo_line_items (line_item_id)');
        $this->addSql('CREATE TABLE sylius_refund_credit_memo_tax_items (credit_memo_id VARCHAR(255) NOT NULL, tax_item_id INT NOT NULL, PRIMARY KEY(credit_memo_id, tax_item_id))');
        $this->addSql('CREATE INDEX IDX_9BBDFBE28E574316 ON sylius_refund_credit_memo_tax_items (credit_memo_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BBDFBE25327F254 ON sylius_refund_credit_memo_tax_items (tax_item_id)');
        $this->addSql('CREATE TABLE sylius_refund_credit_memo_sequence (id INT NOT NULL, idx INT NOT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sylius_refund_customer_billing_data (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, country_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, province_name VARCHAR(255) DEFAULT NULL, province_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sylius_refund_line_item (id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, unit_net_price INT NOT NULL, unit_gross_price INT NOT NULL, net_value INT NOT NULL, gross_value INT NOT NULL, tax_amount INT NOT NULL, tax_rate VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sylius_refund_refund (id INT NOT NULL, order_id INT DEFAULT NULL, amount INT NOT NULL, refunded_unit_id INT DEFAULT NULL, type VARCHAR(256) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DEF86A0E8D9F6D38 ON sylius_refund_refund (order_id)');
        $this->addSql('COMMENT ON COLUMN sylius_refund_refund.type IS \'(DC2Type:sylius_refund_refund_type)\'');
        $this->addSql('CREATE TABLE sylius_refund_refund_payment (id INT NOT NULL, payment_method_id INT DEFAULT NULL, order_id INT DEFAULT NULL, amount INT NOT NULL, currency_code VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EC283EA55AA1164F ON sylius_refund_refund_payment (payment_method_id)');
        $this->addSql('CREATE INDEX IDX_EC283EA58D9F6D38 ON sylius_refund_refund_payment (order_id)');
        $this->addSql('CREATE TABLE sylius_refund_shop_billing_data (id INT NOT NULL, company VARCHAR(255) DEFAULT NULL, tax_id VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sylius_refund_tax_item (id INT NOT NULL, label VARCHAR(255) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F333178CED90B FOREIGN KEY (from_id) REFERENCES sylius_refund_customer_billing_data (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F333130354A65 FOREIGN KEY (to_id) REFERENCES sylius_refund_shop_billing_data (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F333172F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo ADD CONSTRAINT FK_5C4F33318D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90E8E574316 FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items ADD CONSTRAINT FK_1453B90EA7CBD339 FOREIGN KEY (line_item_id) REFERENCES sylius_refund_line_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items ADD CONSTRAINT FK_9BBDFBE28E574316 FOREIGN KEY (credit_memo_id) REFERENCES sylius_refund_credit_memo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items ADD CONSTRAINT FK_9BBDFBE25327F254 FOREIGN KEY (tax_item_id) REFERENCES sylius_refund_tax_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_refund ADD CONSTRAINT FK_DEF86A0E8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment ADD CONSTRAINT FK_EC283EA55AA1164F FOREIGN KEY (payment_method_id) REFERENCES sylius_payment_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment ADD CONSTRAINT FK_EC283EA58D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE sylius_refund_credit_memo_sequence_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_refund_customer_billing_data_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_refund_line_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_refund_refund_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_refund_refund_payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_refund_shop_billing_data_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sylius_refund_tax_item_id_seq CASCADE');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP CONSTRAINT FK_5C4F333178CED90B');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP CONSTRAINT FK_5C4F333130354A65');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP CONSTRAINT FK_5C4F333172F5A1AA');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo DROP CONSTRAINT FK_5C4F33318D9F6D38');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items DROP CONSTRAINT FK_1453B90E8E574316');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_line_items DROP CONSTRAINT FK_1453B90EA7CBD339');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items DROP CONSTRAINT FK_9BBDFBE28E574316');
        $this->addSql('ALTER TABLE sylius_refund_credit_memo_tax_items DROP CONSTRAINT FK_9BBDFBE25327F254');
        $this->addSql('ALTER TABLE sylius_refund_refund DROP CONSTRAINT FK_DEF86A0E8D9F6D38');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment DROP CONSTRAINT FK_EC283EA55AA1164F');
        $this->addSql('ALTER TABLE sylius_refund_refund_payment DROP CONSTRAINT FK_EC283EA58D9F6D38');
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
}
