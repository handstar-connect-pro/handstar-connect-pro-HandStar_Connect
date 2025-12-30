# Validation des Énumérations

## 📋 Introduction

Ce document décrit comment utiliser le service `EnumValidationService` pour valider les valeurs d'énumération dans l'application HandStar Connect, plutôt que d'utiliser des chaînes en dur.

## 🎯 Problème

Lors de l'insertion manuelle de données (comme nous l'avons fait pour le joueur "Théo Laufray"), nous avons rencontré des erreurs dues à l'utilisation de chaînes en dur incorrectes :

```php
// ❌ MAUVAIS - Chaînes en dur
$gender = 'male';        // Devrait être 'MALE'
$handedness = 'right';   // Devrait être 'RIGHT_HANDED'
$position = 'goalkeeper'; // Devrait être 'GOALKEEPER'
$division = 'national_1'; // Devrait être 'NATIONALE_1'
```

## ✅ Solution

### Service `EnumValidationService`

Nous avons créé un service centralisé pour valider les énumérations :

```php
use App\Services\EnumValidationService;

// Validation simple
$isValid = EnumValidationService::validatePersonGender('MALE'); // true
$isValid = EnumValidationService::validatePersonGender('male'); // false

// Récupération d'une instance validée
try {
    $enum = EnumValidationService::getValidatedEnum(
        \App\Enums\PlayerLaterality::class,
        'RIGHT_HANDED'
    );
    // $enum est une instance de PlayerLaterality::RIGHT_HANDED
} catch (\InvalidArgumentException $e) {
    // Gestion de l'erreur
}

// Liste des valeurs valides
$validValues = EnumValidationService::getValidValues(
    \App\Enums\PersonGender::class
);
// Retourne: ['MALE', 'FEMALE']
```

### Exemples d'Utilisation

#### 1. Dans les Contrôleurs

```php
// AVANT
public function createPlayer(array $data)
{
    // Validation manuelle avec chaînes en dur
    if (!in_array($data['gender'], ['MALE', 'FEMALE'])) {
        throw new \InvalidArgumentException('Genre invalide');
    }
}

// APRÈS
public function createPlayer(array $data)
{
    // Utilisation du service de validation
    if (!EnumValidationService::validatePersonGender($data['gender'])) {
        throw new \InvalidArgumentException(
            EnumValidationService::getErrorMessage(
                \App\Enums\PersonGender::class,
                $data['gender']
            )
        );
    }
}
```

#### 2. Dans les DTOs

```php
class PlayerCreationDto
{
    public function __construct(
        public string $gender,
        public string $handedness
    ) {}
    
    public function validate(): array
    {
        $errors = [];
        
        if (!EnumValidationService::validatePersonGender($this->gender)) {
            $errors[] = EnumValidationService::getErrorMessage(
                \App\Enums\PersonGender::class,
                $this->gender
            );
        }
        
        // ... validation des autres champs
        
        return $errors;
    }
}
```

#### 3. Dans les Formulaires

```php
// Dans un Type de formulaire
$builder->add('gender', ChoiceType::class, [
    'choices' => EnumValidationService::getValidValues(
        \App\Enums\PersonGender::class
    ),
    'constraints' => [
        new Callback(function($value, ExecutionContextInterface $context) {
            if (!EnumValidationService::validatePersonGender($value)) {
                $context->addViolation(
                    EnumValidationService::getErrorMessage(
                        \App\Enums\PersonGender::class,
                        $value
                    )
                );
            }
        }),
    ],
]);
```

## 📊 Énumérations Disponibles

| Énumération | Valeurs Valides | Valeur par Défaut |
|-------------|----------------|-------------------|
| `PersonGender` | `MALE`, `FEMALE` | `MALE` |
| `PlayerLaterality` | `RIGHT_HANDED`, `LEFT_HANDED`, `AMBIDEXTROUS` | `RIGHT_HANDED` |
| `PlayerPosition` | `GOALKEEPER`, `LEFT_WING`, `LEFT_BACK`, `CENTER_BACK`, `RIGHT_BACK`, `RIGHT_WING`, `PIVOT` | `CENTER_BACK` |
| `LevelDivision` | `LIQUI_MOLY_STARLIGUE`, `PROLIGUE`, `LIGUE_BUTAGAZ_ENERGIE`, `D2_FEMININE`, `NATIONALE_1_ELITE`, `NATIONALE_1`, `NATIONALE_2`, `NATIONALE_3`, `PRENATIONAL`, `EXCELLENCE_REGIONALE`, `HONNEUR_REGIONALE`, `DEPARTEMENTAL` | `NATIONALE_1` |
| `UserProfil` | `PLAYER`, `COACH`, `REFEREE`, `TECHNICAL_DIRECTOR`, `VIDEO_ANALYST`, `PHYSICAL_TRAINER`, `PHYSIO_THERAPIST`, `MENTAL_TRAINER` | `PLAYER` |
| `AnnouncementType` | `PLAYER_SEARCH`, `COACH_SEARCH`, `CLUB_SEARCH`, `TRAINING_SESSION`, `TOURNAMENT`, `FRIENDLY_MATCH` | `PLAYER_SEARCH` |
| `AnnouncementStatus` | `PENDING`, `PUBLISHED`, `CLOSED`, `CANCELLED` | `PENDING` |
| `ResponseStatus` | `PENDING`, `ACCEPTED`, `REJECTED`, `CANCELLED` | `PENDING` |

## 🔧 Routes d'Exemple

Nous avons créé un contrôleur d'exemple avec des routes pour tester la validation :

1. **Validation simple** : `GET /admin/examples/enum-validation/validate-person-gender?gender=MALE`
2. **Récupération d'énumération** : `GET /admin/examples/enum-validation/get-validated-enum?value=RIGHT_HANDED&enum_class=App\Enums\PlayerLaterality`
3. **Création de joueur** : `POST /admin/examples/enum-validation/create-player-example`
4. **Liste des valeurs** : `GET /admin/examples/enum-validation/all-valid-values`
5. **Exemple DTO** : `POST /admin/examples/enum-validation/dto-example`

## 🚀 Bonnes Pratiques

### À Faire ✅

```php
// ✅ UTILISER les constantes d'énumération
use App\Enums\PersonGender;

$gender = PersonGender::MALE->value; // 'MALE'
$enum = PersonGender::from('MALE');  // Instance de PersonGender::MALE

// ✅ UTILISER le service de validation
if (EnumValidationService::validatePersonGender($input)) {
    // Traitement
}

// ✅ UTILISER tryFrom pour éviter les exceptions
$enum = PersonGender::tryFrom($input);
if ($enum !== null) {
    // Valeur valide
}
```

### À Éviter ❌

```php
// ❌ ÉVITER les chaînes en dur
$gender = 'male'; // Risque d'erreur

// ❌ ÉVITER les tableaux de validation manuels
$validGenders = ['MALE', 'FEMALE']; // Peut devenir obsolète

// ❌ ÉVITER from() sans validation préalable
try {
    $enum = PersonGender::from($input); // Peut lancer une exception
} catch (\ValueError $e) {
    // Gestion d'erreur
}
```

## 🔍 Dépannage

### Erreur Courante : "ValueError: X is not a valid backing value for enum Y"

**Cause** : Utilisation d'une chaîne en dur qui ne correspond à aucune valeur d'énumération.

**Solution** :
1. Vérifier les valeurs valides avec `EnumValidationService::getValidValues()`
2. Utiliser `EnumValidationService::validateEnum()` avant d'utiliser la valeur
3. Consulter la documentation des énumérations ci-dessus

### Exemple de Correction

```php
// AVANT (problème)
$player = new Player();
$player->setGender('male'); // ❌ Erreur: 'male' n'est pas valide

// APRÈS (solution)
use App\Enums\PersonGender;

$player = new Player();
$player->setGender(PersonGender::MALE->value); // ✅ Correct
// OU
if (EnumValidationService::validatePersonGender('MALE')) {
    $player->setGender('MALE'); // ✅ Correct
}
```

## 📈 Avantages

1. **Maintenabilité** : Les changements d'énumération sont centralisés
2. **Sécurité** : Validation stricte des valeurs d'entrée
3. **Documentation** : Les valeurs valides sont documentées dans le code
4. **Débogage** : Messages d'erreur clairs et informatifs
5. **Évolutivité** : Facile d'ajouter de nouvelles énumérations

## 🔗 Références

- [Documentation PHP Enums](https://www.php.net/manual/fr/language.types.enumerations.php)
- [Code source des énumérations](src/Enums/)
- [Service de validation](src/Services/EnumValidationService.php)
- [Contrôleur d'exemple](src/Controller/Admin/EnumValidationExampleController.php)
