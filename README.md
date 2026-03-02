# Gestion Hospitaliere PHP (Vanilla + SQLite)

Application web universitaire de gestion hospitaliere avec authentification et modules CRUD:

- Beneficiaires
- Affectations (ALD / pathologies)
- Prestations

Le projet tourne sans framework PHP, avec SQLite en base locale et jQuery pour les appels AJAX.

## Apercu

![Page accueil](docs/images/page-accueil.png)

## Stack

- PHP 8.x (vanilla)
- SQLite3
- JavaScript + jQuery
- Docker / Docker Compose
- GitHub Actions (pipeline CI)

## Demarrage Rapide (Docker)

Pre-requis:

- Docker Desktop ou Docker Engine
- Docker Compose (`docker compose` ou `docker-compose`)

Depuis la racine du projet:

```bash
docker-compose up -d
```

Puis ouvrir:

- http://localhost:8080

Compte de demo:

- Login: `admin`
- Mot de passe: `admin123`

## Base de Donnees

- `tables.sql`: schema de reference
- `bdd/base.db`: base metier SQLite (non versionnee)
- `bdd/user.db`: base auth SQLite (non versionnee)

Les fichiers `.db` ne sont pas pushes (taille + donnees locales).  
La creation de DB de demo est geree automatiquement par:

- `scripts/init_demo_dbs.php`

## Reinitialiser la DB Demo

Pour repartir d'une base propre:

```bash
docker-compose down -v
RESET_DEMO_DB=1 docker-compose up -d
```

## Lancement Local Sans Docker

Si PHP est installe localement:

```bash
php -S 127.0.0.1:8000
```

Puis ouvrir:

- http://127.0.0.1:8000

## Pipeline CI

Workflow GitHub Actions: `.github/workflows/ci.yml`

La CI execute:

1. Lint PHP (`php -l`) sur tous les fichiers `.php`
2. Demarrage de l'app via Docker Compose
3. Smoke tests HTTP des 3 ajouts critiques:
   - `ajouterBeneficiaire.php`
   - `ajouterAffection.php`
   - `ajouterPrestation.php`

Le job echoue si:

- un fichier PHP est invalide
- l'app ne demarre pas
- une reponse JSON d'endpoint est invalide ou incoherente

## Donnees de Test (Exemple)

### Beneficiaire

- `BEN_NIR_IDT`: `17777777777777771`
- `BEN_NAI_ANN`: `1990`
- `BEN_RES_DPT`: `075`
- `BEN_SEX_COD`: `1`
- `BEN_DCD_AME`: vide

### Affectation

- `BEN_NIR_IDT`: `17777777777777771`
- `IMB_ALD_NUM`: `19`
- `IMB_ALD_DTD`: `2020-02-01`
- `IMB_ALD_DTF`: `2099-01-01`
- `IMB_ETM_NAT`: `41`
- `MED_MTF_COD`: `D695`

### Prestation

- `BEN_NIR_IDT`: `17777777777777771`
- `EXE_SOI_DTD`: `2021-03-10`
- `EXE_SOI_DTF`: `2021-03-12`
- `PFS_PRE_CRY`: `AAAAAAAAAAAAAAAAAAAAAAAAA`
- `PRS_NAT_REF`: `1111`
- `FLX_DIS_DTD`: `2021-04-01`
- `PSE_ACT_SPE`: `12345678`
- `BEN_CMU_TOP`: `0`
- `PRE_PRE_DTD`: `2021-03-01`
- `PRS_ACT_QTE`: `1`

## Correctifs Importants Deja Appliques

- Correction des warnings `header()` (redirection login/logout)
- Suppression des problemes BOM/encodage qui cassaient les headers
- Robustesse des endpoints d'ajout (JSON propre, validation plus claire)
- Requetes preparees sur les ajouts
- Gestion plus explicite des erreurs front (plus de "silence" au submit)
- Normalisation des fins de ligne (`.gitattributes`)

## Arborescence Utile

- `index.php`: login + navigation
- `pages/`: vues HTML/PHP
- `php/`: endpoints CRUD/recherche/select/update/delete
- `script/`: JS front (AJAX)
- `styles/`: CSS
- `scripts/init_demo_dbs.php`: bootstrap DB demo

## Limites Connues

- Projet vanilla (pas de framework, pas de tests unitaires natifs)
- Certaines regles metier sont simplifiees pour le contexte universitaire
- Validation front encore basee majoritairement sur highlighting de labels
