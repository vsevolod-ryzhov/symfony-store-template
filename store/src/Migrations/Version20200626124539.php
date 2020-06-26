<?php /** @noinspection ALL */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626124539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_categories (id INT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A9941943A977936C ON product_categories (tree_root)');
        $this->addSql('CREATE INDEX IDX_A9941943727ACA70 ON product_categories (parent_id)');
        $this->addSql('CREATE INDEX lft_ix ON product_categories (lft)');
        $this->addSql('CREATE INDEX rgt_ix ON product_categories (rgt)');
        $this->addSql('CREATE INDEX lvl_ix ON product_categories (lvl)');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A9941943A977936C FOREIGN KEY (tree_root) REFERENCES product_categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A9941943727ACA70 FOREIGN KEY (parent_id) REFERENCES product_categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_products ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_products ADD CONSTRAINT FK_780A30CD12469DE2 FOREIGN KEY (category_id) REFERENCES product_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_780A30CD12469DE2 ON product_products (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_categories DROP CONSTRAINT FK_A9941943A977936C');
        $this->addSql('ALTER TABLE product_categories DROP CONSTRAINT FK_A9941943727ACA70');
        $this->addSql('ALTER TABLE product_products DROP CONSTRAINT FK_780A30CD12469DE2');
        $this->addSql('DROP SEQUENCE product_categories_id_seq CASCADE');
        $this->addSql('DROP TABLE product_categories');
        $this->addSql('DROP INDEX IDX_780A30CD12469DE2');
        $this->addSql('ALTER TABLE product_products DROP category_id');
    }
}
