# Couverture de Code - MyWeeklyAllowance

## État Actuel

Tous les chemins de code de `AllowanceAccount` sont testés :

### Méthodes couvertes :
- ✅ `__construct()` - avec validation weeklyAllowance > 0
- ✅ `getBalance()` - testé dans tous les scénarios
- ✅ `getWeeklyAllowance()` - testé
- ✅ `getHistory()` - testé avec vérification des timestamps
- ✅ `deposit()` - avec validation amount > 0
- ✅ `recordExpense()` - avec validation amount > 0 et fonds insuffisants
- ✅ `applyWeeklyAllowance()` - testé avec vérification historique

### Cas limites testés :
- ✅ Allocation hebdomadaire doit être positive
- ✅ Dépôt doit être positif
- ✅ Dépense doit être positive
- ✅ Dépense avec fonds insuffisants lève exception et ne modifie pas le solde
- ✅ Historique contient timestamps pour toutes les opérations

## Mesure Automatique de Couverture

Pour générer un rapport de couverture automatique, installer l'extension `pcov` :

```bash
pecl install pcov
```

Puis ajouter dans `php.ini` :
```ini
extension=pcov.so
```

Ensuite, exécuter :
```bash
./vendor/bin/phpunit --coverage-text
# ou pour un rapport HTML :
./vendor/bin/phpunit --coverage-html coverage/
```

