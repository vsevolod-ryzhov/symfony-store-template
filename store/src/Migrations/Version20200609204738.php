<?php /** @noinspection ALL */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609204738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE product_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_products (id INT NOT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, sku VARCHAR(255) NOT NULL, price_price NUMERIC(7, 2) NOT NULL, price_old_price NUMERIC(7, 2) NOT NULL, warehouse INT NOT NULL, weight DOUBLE PRECISION NOT NULL, description TEXT DEFAULT NULL, is_deleted BOOLEAN NOT NULL DEFAULT (false), sort INT DEFAULT 1, meta_name VARCHAR(255) DEFAULT NULL, meta_keywords TEXT DEFAULT NULL, meta_description TEXT DEFAULT NULL, image_order JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_780A30CDF47645AE ON product_products (url)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_780A30CDF9038C4 ON product_products (sku)');
        $this->addSql('COMMENT ON COLUMN product_products.created_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product_products.updated_date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE product_products_id_seq CASCADE');
        $this->addSql('DROP TABLE product_products');
    }
}
