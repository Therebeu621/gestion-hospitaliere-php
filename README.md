# gestion-hospitaliere-php

Application web PHP/SQLite de gestion hospitaliere (beneficiaires, prestations, affectations).

## Stack

- PHP (vanilla)
- SQLite
- JavaScript (jQuery)
- Docker / docker-compose (optionnel)

## Lancement rapide avec Docker

Prerequis:

- Docker
- docker-compose (v1 ou v2)

Depuis la racine du projet:

```bash
docker-compose up -d
```

Puis ouvre:

- http://localhost:8080

Identifiants de demo:

- login: `admin`
- mot de passe: `admin123`

Notes:

- Au demarrage, `scripts/init_demo_dbs.php` cree automatiquement `bdd/base.db` et `bdd/user.db` si absents.
- En Docker, `bdd/` est stocke dans un volume Docker (`app_bdd`) pour eviter les conflits avec tes DB locales.
- Pour forcer une recreation des DB de demo:

```bash
RESET_DEMO_DB=1 docker-compose up
```

- Pour repartir de zero (volume supprime):

```bash
docker-compose down -v
docker-compose up -d
```

## Lancement local sans Docker

Si PHP est installe:

```bash
php -S 127.0.0.1:8000
```

Puis ouvre:

- http://127.0.0.1:8000

## Base de donnees

- `tables.sql` contient un schema de reference.
- Les fichiers `*.db` sont ignores par Git (taille importante / donnees locales).
- Le dossier `bdd/` est garde via `.gitkeep`.

## Arborescence utile

- `index.php`: login + navigation principale
- `pages/`: interfaces
- `php/`: endpoints CRUD/recherche
- `script/`: JS front (AJAX)
- `scripts/init_demo_dbs.php`: init DB demo
