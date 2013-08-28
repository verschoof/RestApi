<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130828101852 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, middlename VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE author_movie (author_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_BD6D2A2F675F31B (author_id), INDEX IDX_BD6D2A28F93B6FC (movie_id), PRIMARY KEY(author_id, movie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user_movie (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_FF9C0937A76ED395 (user_id), INDEX IDX_FF9C09378F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE author_movie ADD CONSTRAINT FK_BD6D2A2F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE author_movie ADD CONSTRAINT FK_BD6D2A28F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C0937A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C09378F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE author_movie DROP FOREIGN KEY FK_BD6D2A2F675F31B");
        $this->addSql("ALTER TABLE author_movie DROP FOREIGN KEY FK_BD6D2A28F93B6FC");
        $this->addSql("ALTER TABLE user_movie DROP FOREIGN KEY FK_FF9C09378F93B6FC");
        $this->addSql("DROP TABLE author");
        $this->addSql("DROP TABLE author_movie");
        $this->addSql("DROP TABLE movie");
        $this->addSql("DROP TABLE user_movie");
    }
}
