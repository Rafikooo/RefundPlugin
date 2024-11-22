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

final class Version20180820132147 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_refund_payment DROP number');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_refund_payment ADD number VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
