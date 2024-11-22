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

final class Version20190517064223 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_refund_refund CHANGE type type VARCHAR(256) NOT NULL COMMENT \'(DC2Type:sylius_refund_refund_type)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_refund_refund CHANGE type type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
