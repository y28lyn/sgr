SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

--
-- Base de données : `sgr_mono`
--
CREATE DATABASE IF NOT EXISTS `sgr_mono` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;

USE `sgr_mono`;

CREATE TABLE `categorie_plat` (
  `id_cat` int(11) NOT NULL,
  `nom_cat` varchar(30) NOT NULL,
  `ordre_affichage_cat` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `ligne_ticket` (
  `id_ligne_ticket` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `id_plat` int(11) NOT NULL,
  `commentaire` varchar(255) DEFAULT NULL,
  `Etat` enum(
    'En saisie',
    'Demandé',
    'En cours',
    'Prêt',
    'Servi'
  ) DEFAULT 'En saisie'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nom_menu` varchar(50) NOT NULL DEFAULT 'nom du menu',
  `PU` float NOT NULL DEFAULT '0',
  `description` text,
  `date_menu` date DEFAULT NULL,
  `vu` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `menu_contient_plat` (
  `id_menu` int(11) NOT NULL,
  `id_plat` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `plat` (
  `id_plat` int(11) NOT NULL,
  `nom_plat` varchar(50) NOT NULL DEFAULT 'nom du plat',
  `description` text NOT NULL,
  `type_plat` varchar(10) NOT NULL DEFAULT 'entplades',
  `PU_carte` float(11, 2) NOT NULL DEFAULT '0.00',
  `id_sous_cat` int(11) DEFAULT '1',
  `vu` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `sgr_table` (
  `id_table` int(11) NOT NULL,
  `numero_table` int(11) NOT NULL,
  `type_table` varchar(3) NOT NULL,
  `id_serveur` int(11) DEFAULT NULL,
  `vu` int(1) NOT NULL,
  `left_position` INT NOT NULL,
  `top_position` INT NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;



CREATE TABLE `sous_categorie` (
  `id_sous_cat` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `nom_sous_cat` varchar(30) NOT NULL,
  `ordre_aff_sous_cat` int(11) NOT NULL,
  `couleur` varchar(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `ticket` (
  `id_ticket` int(11) NOT NULL,
  `id_table` int(11) NOT NULL,
  `nb_couvert` int(11) NOT NULL,
  `statut` varchar(11) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `ordre` int(11) NOT NULL,
  `date_c` datetime DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `role` varchar(30) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

ALTER TABLE
  `categorie_plat`
ADD
  PRIMARY KEY (`id_cat`);

ALTER TABLE
  `ligne_ticket`
ADD
  PRIMARY KEY (`id_ligne_ticket`),
ADD
  KEY `id_ticket` (`id_ticket`),
ADD
  KEY `id_plat` (`id_plat`);

ALTER TABLE
  `menu`
ADD
  PRIMARY KEY (`id_menu`);

ALTER TABLE
  `menu_contient_plat`
ADD
  PRIMARY KEY (`id_menu`, `id_plat`),
ADD
  KEY `id_plat` (`id_plat`);

ALTER TABLE
  `plat`
ADD
  PRIMARY KEY (`id_plat`),
ADD
  KEY `fk_plat_souscat` (`id_sous_cat`);

ALTER TABLE
  `sgr_table`
ADD
  PRIMARY KEY (`id_table`),
ADD
  KEY `sgr_table_ibfk_1` (`id_serveur`);

ALTER TABLE
  `sous_categorie`
ADD
  PRIMARY KEY (`id_sous_cat`),
ADD
  KEY `fk_souscat_cat` (`id_cat`);

ALTER TABLE
  `ticket`
ADD
  PRIMARY KEY (`id_ticket`),
ADD
  KEY `ticket_ibfk_1` (`id_table`);

ALTER TABLE
  `user`
ADD
  PRIMARY KEY (`id_user`);

ALTER TABLE
  `categorie_plat`
MODIFY
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 4;

ALTER TABLE
  `ligne_ticket`
MODIFY
  `id_ligne_ticket` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 22408;

ALTER TABLE
  `menu`
MODIFY
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 60;

ALTER TABLE
  `plat`
MODIFY
  `id_plat` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 106;

ALTER TABLE
  `sgr_table`
MODIFY
  `id_table` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 11;

ALTER TABLE
  `sous_categorie`
MODIFY
  `id_sous_cat` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 19;

ALTER TABLE
  `ticket`
MODIFY
  `id_ticket` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 51;

ALTER TABLE
  `user`
MODIFY
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 16;

ALTER TABLE
  `ligne_ticket`
ADD
  CONSTRAINT `ligne_ticket_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD
  CONSTRAINT `ligne_ticket_ibfk_2` FOREIGN KEY (`id_plat`) REFERENCES `plat` (`id_plat`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE
  `menu_contient_plat`
ADD
  CONSTRAINT `menu_contient_plat_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`),
ADD
  CONSTRAINT `menu_contient_plat_ibfk_2` FOREIGN KEY (`id_plat`) REFERENCES `plat` (`id_plat`);

ALTER TABLE
  `plat`
ADD
  CONSTRAINT `fk_plat_souscat` FOREIGN KEY (`id_sous_cat`) REFERENCES `sous_categorie` (`id_sous_cat`);

ALTER TABLE
  `sgr_table`
ADD
  CONSTRAINT `sgr_table_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `user` (`id_user`);

ALTER TABLE
  `sous_categorie`
ADD
  CONSTRAINT `fk_souscat_cat` FOREIGN KEY (`id_cat`) REFERENCES `categorie_plat` (`id_cat`);

ALTER TABLE
  `ticket`
ADD
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`id_table`) REFERENCES `sgr_table` (`id_table`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;