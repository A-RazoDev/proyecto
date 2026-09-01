<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260901161454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, nombre_rol VARCHAR(50) NOT NULL, descripcion VARCHAR(150) DEFAULT NULL, activo TINYINT DEFAULT 1 NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, nombre_completo VARCHAR(100) NOT NULL, correo_electronico VARCHAR(100) NOT NULL, nombre_usuario VARCHAR(50) NOT NULL, clave_acceso VARCHAR(255) NOT NULL, activo TINYINT DEFAULT 1 NOT NULL, rol_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_EF687F2AE7A59DA (correo_electronico), UNIQUE INDEX UNIQ_EF687F2D67CF11D (nombre_usuario), INDEX IDX_EF687F24BAB96C (rol_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE usuarios ADD CONSTRAINT FK_EF687F24BAB96C FOREIGN KEY (rol_id) REFERENCES roles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuarios DROP FOREIGN KEY FK_EF687F24BAB96C');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE usuarios');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
