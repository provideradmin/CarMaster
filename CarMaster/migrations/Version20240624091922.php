<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240624091922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update the orders table to include parts and materials relations.';
    }

    public function up(Schema $schema): void
    {
        // Ensure that 'order_id' is compatible with 'id' in 'order' table
        $this->addSql('CREATE TABLE order_parts (
            order_id INT UNSIGNED NOT NULL, 
            part_id INT UNSIGNED NOT NULL, 
            INDEX IDX_23A0E66DDCDC965 (order_id), 
            INDEX IDX_23A0E66E1C0B6F5 (part_id), 
            PRIMARY KEY(order_id, part_id)
        )');

        $this->addSql('CREATE TABLE order_materials (
            order_id INT UNSIGNED NOT NULL, 
            material_id INT UNSIGNED NOT NULL, 
            INDEX IDX_4727EF6E8DDCDC965 (order_id), 
            INDEX IDX_4727EF6E1E3AF00F (material_id), 
            PRIMARY KEY(order_id, material_id)
        )');

        $this->addSql('ALTER TABLE order_parts 
            ADD CONSTRAINT FK_23A0E66DDCDC965 
            FOREIGN KEY (order_id) 
            REFERENCES `order` (id) 
            ON DELETE CASCADE');

        $this->addSql('ALTER TABLE order_parts 
            ADD CONSTRAINT FK_23A0E66E1C0B6F5 
            FOREIGN KEY (part_id) 
            REFERENCES part (id) 
            ON DELETE CASCADE');

        $this->addSql('ALTER TABLE order_materials 
            ADD CONSTRAINT FK_4727EF6E8DDCDC965 
            FOREIGN KEY (order_id) 
            REFERENCES `order` (id) 
            ON DELETE CASCADE');

        $this->addSql('ALTER TABLE order_materials 
            ADD CONSTRAINT FK_4727EF6E1E3AF00F 
            FOREIGN KEY (material_id) 
            REFERENCES material (id) 
            ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_parts');
        $this->addSql('DROP TABLE order_materials');
    }
}
