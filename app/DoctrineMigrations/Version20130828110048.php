<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * This migration will add default data
 * To remove this data use follow the next steps (warning: down will truncate tables)
 *
 * - app/console doctrine:migrations:execute 20130828110048 --down
 * - app/console doctrine:migrations:version 20130828110048 --add
 *
 */
class Version20130828110048 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("INSERT INTO `author` (`id`, `firstname`, `middlename`, `lastname`) VALUES
            (1, 'Jennifer', '', 'Aniston'),
            (2, 'Jason', '', 'Sudeikis'),
            (3, 'Will', '', 'Poulter'),
            (4, 'Emma', '', 'Roberts'),
            (5, 'Ed', '', 'Helms'),
            (6, 'Nick', '', 'Offerman'),
            (7, 'Jim', '', 'Carrey'),
            (8, 'Jonah', '', 'Hill'),
            (9, 'Channing', '', 'Tatum');
        ");

        $this->addSql("INSERT INTO `movie` (`id`, `title`, `description`) VALUES
            (1, 'We''re the Millers', 'A veteran pot dealer creates a fake family as part of his plan to move a huge shipment of weed into the U.S. from Mexico.'),
            (2, 'Bruce Almighty', 'A guy who complains about God too often is given almighty powers to teach him how difficult it is to run the world.'),
            (3, '21 Jump Street', 'A pair of underachieving cops are sent back to a local high school to blend in and bring down a synthetic drug ring.');
        ");

        $this->addSql("INSERT INTO `user` (`id`, `firstname`, `middlename`, `lastname`, `email`) VALUES
            (1, 'Mitch', '', 'unknown', 'email@domain.com');
        ");

        $this->addSql("INSERT INTO `author_movie` (`author_id`, `movie_id`) VALUES
            (1, 1),
            (1, 2),
            (2, 1),
            (3, 1),
            (4, 1),
            (5, 1),
            (6, 1),
            (6, 3),
            (7, 2),
            (8, 3),
            (9, 3);
        ");

        $this->addSql("INSERT INTO `user_movie` (`user_id`, `movie_id`) VALUES
            (1, 1),
            (1, 2),
            (1, 3);
        ");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("TRUNCATE TABLE `author`;");
        $this->addSql("TRUNCATE TABLE `author_movie`;");
        $this->addSql("TRUNCATE TABLE `movie`;");
        $this->addSql("TRUNCATE TABLE `user`;");
        $this->addSql("TRUNCATE TABLE `user_movie`;");
    }
}
