# MyWeeklyAllowance

Module de gestion d'argent de poche pour adolescents, développé selon la méthode TDD (Test Driven Development).

## Auteur

**Victor Chabeau**

## Description

L'application MyWeeklyAllowance permet aux parents de gérer un "porte-monnaie virtuel" pour leurs ados. Chaque adolescent a un compte d'argent de poche, et chaque parent peut :

- créer un compte pour un ado
- déposer de l'argent
- enregistrer des dépenses
- fixer une allocation hebdomadaire automatique

## Installation

```bash
composer install
```

## Exécution des tests

```bash
./vendor/bin/phpunit
```

## Structure du projet

```
TDD-PhpUnit/
├── src/                    # Code source
│   ├── AllowanceAccount.php
│   ├── Clock.php
│   └── SystemClock.php
├── tests/                  # Tests unitaires
│   └── AllowanceAccountTest.php
├── composer.json
├── phpunit.xml
└── README.md
```

## Méthodologie TDD

Ce projet a été développé en suivant les phases TDD :

1. **RED** : Rédaction des tests unitaires décrivant le comportement attendu
2. **BLUE** : Implémentation minimale du code pour faire passer les tests
3. **GREEN** : Refactoring du code tout en gardant les tests verts
4. **Vérification** : Analyse de la couverture de code

## Fonctionnalités

- Création de compte avec allocation hebdomadaire
- Dépôt d'argent avec validation
- Enregistrement de dépenses avec vérification des fonds
- Application automatique de l'allocation hebdomadaire
- Historique complet des opérations avec timestamps

## Couverture de code

Voir [COVERAGE.md](COVERAGE.md) pour les détails sur la couverture de code.

