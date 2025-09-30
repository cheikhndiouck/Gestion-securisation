# Gestion Sécurisation - Senelec (Projet-Stage)

Application web PHP (XAMPP) pour la gestion des réceptions de travaux, des bons de sortie, de l'inventaire et de la facturation, avec export Excel.

## Prérequis
- XAMPP (Apache + MariaDB/MySQL + PHP 8.x)
- Navigateur web

## Installation
1) Cloner ou copier le projet dans `C:/xampp/htdocs/Projet-Stage`.
2) Démarrer Apache et MySQL depuis le panneau XAMPP.
3) Créer la base de données `gestion-database` dans phpMyAdmin.
4) Importer le schéma minimal:
   - Importer `sql/create_tables.sql` pour les tables de réception (`fiche_reception`, `materiels_reception`, `receptions_compteurs`).
   - Vous pouvez aussi exécuter `sql/verify_tables.sql` qui crée ces tables si elles n'existent pas.
5) Vérifier la connexion BD dans `php/BD connexion/connexion.php` (par défaut: `root` sans mot de passe sur `gestion-database`).

Remarque: L'application utilise aussi d'autres tables (ex: `materiel`, `bon_sortie`, `inventaire_materiel`, `users`). Assurez-vous que ces tables existent dans votre base. Si besoin, créez-les selon vos besoins ou votre dump existant.

## Créer un utilisateur administrateur
Un script d’exemple existe pour créer un compte admin:
- Fichier: `php/create_user.php`
- Valeurs par défaut: email `admin@test.com`, mot de passe `1234`
- Exécuter via navigateur: `http://localhost/Projet-Stage/php/create_user.php`

Vous pouvez ensuite vous connecter via la page de login.

## Lancement de l’application
- URL de connexion: `http://localhost/Projet-Stage/views/index.php`
- Après connexion, accédez aux modules via l’entête.

## Modules principaux
- Réception (`views/Reception.php`):
  - Saisie d’une nouvelle réception (GIE, zone, date, matériels, compteurs/avis).
  - Liste des dernières réceptions et export Excel `php/export_reception.php`.

- Bons de sortie (`views/Bons.php`):
  - Création d’un bon (numérotation auto `YYYY-MM-000001`).
  - Liste des bons, lien vers l’inventaire, suppression d’un bon et de ses inventaires.
  - Export Excel `php/export_bons.php`.

- Facturation: pages présentes (`views/Facturation.php`, `views/facture_valide.php`) et scripts d’export `php/export_facturation.php` selon votre besoin.

## Exports
- Réceptions: `php/export_reception.php`
- Bons: `php/export_bons.php`
- Facturation: `php/export_facturation.php`

## Structure du projet (extrait)
- `views/` pages principales (login, Réception, Bons, etc.)
- `php/` traitements, accès BD, exports, APIs simples
- `php/BD connexion/connexion.php` connexion PDO à MySQL
- `sql/` scripts de création/vérification des tables
- `js/` scripts front (validation, dynamiques)
- `css/` styles (Bootstrap + personnalisations)

## Configuration BD (par défaut)
Le fichier `php/BD connexion/connexion.php` se connecte à:
```
host: localhost
dbname: gestion-database
user: root
password: (vide)
charset: utf8
```
Adaptez ces paramètres à votre environnement si nécessaire.

## Sécurité et bonnes pratiques
- Changez le mot de passe par défaut et gérez les rôles selon vos besoins.
- Utilisez un mot de passe pour l’utilisateur MySQL en production.
- Filtrez et validez toutes les entrées côté serveur.

## Dépannage
- Page blanche/erreur: consulter les logs Apache et vérifier la base `gestion-database`.
- Problème d’exports: vérifier les droits d’écriture et l’extension PHP utilisée pour Excel (selon l’implémentation des scripts).
- Problème de connexion: mettre à jour `php/BD connexion/connexion.php`.

## Licence
Projet interne (stage). Adapter selon les politiques de votre organisation.

