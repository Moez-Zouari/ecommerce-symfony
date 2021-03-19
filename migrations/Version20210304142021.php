<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304142021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_details_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, usero_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, carrier_name VARCHAR(255) NOT NULL, carrier_price DOUBLE PRECISION NOT NULL, delivery TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F52993987D7C5C1A ON "order" (usero_id)');
        $this->addSql('CREATE TABLE order_details (id INT NOT NULL, orderd_id INT NOT NULL, product VARCHAR(255) NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_845CA2C1230F5F0 ON order_details (orderd_id)');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993987D7C5C1A FOREIGN KEY (usero_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1230F5F0 FOREIGN KEY (orderd_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_details DROP CONSTRAINT FK_845CA2C1230F5F0');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE order_details_id_seq CASCADE');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_details');
    }
}
