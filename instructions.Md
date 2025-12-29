# Instructions du Projet HandStar Connect

## 1. Vue d'ensemble du projet

### Informations générales
- **Dépôt Git** : https://github.com/handstar-connect-pro/HandStar_Connect.git
- **Branche principale** : main ou master
- **Workflow recommandé** : Git Flow ou GitHub Flow

---

## 2. Standards de codage PHP & Symfony

### 2.1 Structure & Architecture

**Déclarations strictes**
- `declare(strict_types=1);` obligatoire dans tous les fichiers PHP

**Syntaxe moderne**
- Attributs PHP 8+ : Utiliser exclusivement `#[Route]`, `#[ORM\Entity]` au lieu des annotations PHPDoc
- Type Hints : Typer tous les paramètres, retours et propriétés de méthodes
- Readonly : Utiliser `readonly` pour les propriétés injectées et les services

**Organisation du code**
- Logique Métier : Placer dans des services dédiés (`src/Service/`), jamais dans les contrôleurs
- Form Types : Toujours créer des classes FormType dans `src/Form/`
- Repositories : Requêtes Doctrine complexes dans des méthodes de Repository dédiées
- DTOs : Utiliser pour les API avec validation via `#[Assert\...]`

### 2.2 Architecture des contrôleurs

**Principe Anti-Fat Controller** : Pour éviter les contrôleurs massifs

1. **Règle de séparation** : Si un contrôleur dépasse 2 routes ou si la logique devient complexe :
   - Créer un sous-dossier dans `src/Controller/` portant le nom du domaine
     - Exemple : `src/Controller/Product/`
   - Éclater les actions en plusieurs contrôleurs plus petits
     - Exemple : `CreateProductController.php`, `ListProductsController.php`

2. **Single Action Controller** : Utiliser la méthode `__invoke` si le contrôleur ne gère qu'une seule action

```php
// Exemple de Single Action Controller
class CreateProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    #[Route('/products/create', name: 'product_create')]
    public function __invoke(Request $request): Response
    {
        // Logique unique pour la création
    }
}
```

### 2.3 Injection de dépendances
- **Constructor Injection** : Toujours injecter les dépendances via le constructeur
- **Readonly** : Marquer les propriétés injectées comme `readonly`
- **Interfaces** : Définir des contrats (interfaces) pour les services critiques

```php
class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly PasswordHasherInterface $hasher,
        private readonly EventDispatcherInterface $dispatcher
    ) {}
}
```

### 2.4 Sécurité & Permissions
- **Security Voters** : Pour les permissions complexes, utiliser des Voters plutôt que de la logique conditionnelle
- **Vérification des droits** : Utiliser `$this->isGranted()` ou `$this->denyAccessUnlessGranted()`

### 2.5 Conventions de nommage
- **Variables & Méthodes** : camelCase
- **Classes** : PascalCase
- **Langue** : Anglais uniquement (variables, méthodes, classes, commentaires)

---

## 3. Principes SOLID

Les principes SOLID sont essentiels pour la qualité du code :

### S - Single Responsibility Principle (SRP)
Chaque classe doit avoir une seule responsabilité

```php
// ✅ Bon : Responsabilités séparées
class UserService { /* logique métier */ }
class UserRepository { /* accès données */ }
class UserController { /* orchestration */ }
```

### O - Open/Closed Principle (OCP)
Les entités doivent être ouvertes à l'extension mais fermées à la modification

```php
// ✅ Bon : Extension via interfaces
interface NotificationSenderInterface {
    public function send(User $user, string $message): void;
}

class EmailNotificationSender implements NotificationSenderInterface { }
class SmsNotificationSender implements NotificationSenderInterface { }
```

### L - Liskov Substitution Principle (LSP)
Les objets d'une classe dérivée doivent pouvoir remplacer les objets de la classe de base sans affecter la correction du programme

### I - Interface Segregation Principle (ISP)
Préférer plusieurs interfaces spécifiques plutôt qu'une interface générale

### D - Dependency Inversion Principle (DIP)
Dépendre des abstractions (interfaces) plutôt que des implémentations concrètes

```php
// ✅ Bon : Dépendance à l'interface
public function __construct(
    private readonly NotificationSenderInterface $sender
) {}
```

---

## 4. Commentaires de code

### Règles générales
- Le code doit être commenté pour être facilement compréhensible par tous les développeurs
- Commenter le "pourquoi", pas le "quoi"
- Éviter les commentaires évidents
- Format : Cohérent avec le code (français ou anglais)

### Types de commentaires

**Commentaires simples**
```php
// Commentaire d'une ligne pour expliquer une logique

/*
 * Commentaire multi-lignes
 * pour expliquer une logique complexe
 */
```

**Documentation PHPDoc**
```php
/**
 * Crée un nouvel utilisateur avec validation complète
 *
 * @param CreateUserDto $dto Les données de l'utilisateur
 * @return User L'utilisateur créé
 * @throws ValidationException Si les données sont invalides
 */
public function createUser(CreateUserDto $dto): User
{
    // ...
}
```

### Densité de commentaires
- **Équilibre** : Commenter les parties complexes, laisser le code simple sans commentaires superflus
- **Commenter** : Logique métier complexe, décisions architecturales, workarounds, hacks nécessaires
- **Ne pas commenter** : Ce qui est évident depuis le code lui-même

---

## 5. Qualité de code & Analyse statique

### 5.1 Configuration PHPStan
Le projet utilise PHPStan niveau 9 (maximum) pour l'analyse statique.

**Fichier phpstan.neon.dist** :
```neon
includes:
    - vendor/phpstan/phpstan-symfony/extension.neon
    - vendor/phpstan/phpstan-doctrine/extension.neon

parameters:
    level: 9  # Niveau maximum de strictness

    paths:
        - src
        - tests

    # Fichiers exclus de l'analyse
    excludePaths:
        - src/Kernel.php
        - tests/bootstrap.php
        - src/DataFixtures/*

    # Configuration Symfony
    symfony:
        container_xml_path: var/cache/dev/App_KernelDevDebugContainer.xml
        console_application_loader: tests/console-application.php

    # Configuration Doctrine
    doctrine:
        repositoryClass: App\Repository\BaseRepository
        objectManagerLoader: tests/object-manager.php

    # Ignorer des erreurs spécifiques (utiliser avec parcimonie)
    ignoreErrors:
        - '#Call to an undefined method App\\Entity\\User::getFullName\(\)#'
        -
            message: '#Access to an undefined property#'
            path: src/Legacy/*

    # Analyses strictes activées
    checkMissingIterableValueType: true
    checkGenericClassInNonGenericObjectType: true
    reportUnmatchedIgnoredErrors: false
```

### 5.2 Commandes PHPStan
```bash
# Analyse complète
vendor/bin/phpstan analyse

# Avec niveau spécifique
vendor/bin/phpstan analyse --level=9

# Générer une baseline (ignorer erreurs existantes temporairement)
vendor/bin/phpstan analyse --generate-baseline

# Clear cache
vendor/bin/phpstan clear-result-cache
```

### 5.3 PHP CS Fixer
Pour l'uniformisation du style de code (configuration optionnelle).

```bash
# Installation
composer require --dev friendsofphp/php-cs-fixer

# Exécution
vendor/bin/php-cs-fixer fix
```

---

## 6. Sécurité & Typage fort

### 6.1 Principes de sécurité

**Authentification**
- Toujours utiliser le système d'authentification Symfony (`make:user`, `make:auth`)
- Ne jamais implémenter son propre système d'authentification

**Autorisation**
- Utiliser les Voters pour les permissions complexes
- Utiliser `$this->isGranted()` pour les vérifications d'accès
- Utiliser `$this->denyAccessUnlessGranted()` dans les contrôleurs

**Validation des entrées**
- Valider toutes les entrées utilisateur avec le composant Validator
- Utiliser des contraintes strictes `#[Assert\...]`

**Protection CSRF**
- Activée par défaut sur tous les formulaires Symfony
- Ne jamais désactiver sans raison valable

**Headers de sécurité**
- Configurer CSP, HSTS, X-Frame-Options
- Recommandé : Utiliser NelmioSecurityBundle
```bash
composer require nelmio/security-bundle
```

**Mots de passe**
- Utiliser les password hashers Symfony
- Algorithmes modernes : argon2i ou bcrypt

```yaml
# config/packages/security.yaml
security:
    password_hashers:
        App\Entity\User:
            algorithm: auto
            cost: 12  # Pour bcrypt
```

**Sessions sécurisées**
- Configurer les cookies de session avec httponly, secure, samesite

```yaml
# config/packages/framework.yaml
framework:
    session:
        cookie_secure: auto
        cookie_httponly: true
        cookie_samesite: lax
```

### 6.2 Typage strict

**Déclaration stricte**
```php
<?php

declare(strict_types=1);

namespace App\Service;
```

**Types stricts partout**
```php
// ✅ Bon : Tout est typé
class UserService
{
    private int $maxRetries = 3;

    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function findActiveUsers(): array
    {
        return $this->repository->findBy(['active' => true]);
    }

    public function count(): int
    {
        return $this->repository->count([]);
    }
}
```

**DTOs typés**
```php
class CreateUserDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8)]
        public readonly string $password,

        #[Assert\NotBlank]
        public readonly string $firstName,

        #[Assert\NotBlank]
        public readonly string $lastName
    ) {}
}
```

**Annotations de type avancées**
```php
/**
 * @return array<User>
 */
public function findAll(): array
{
    return $this->repository->findAll();
}

/**
 * @param array<string, mixed> $criteria
 */
public function findBy(array $criteria): ?User
{
    // ...
}
```

---

## 7. Tests

### 7.1 Stratégie de tests
- **Tests unitaires** : Pour les services et la logique métier
- **Tests fonctionnels** : Pour les contrôleurs et l'intégration
- **Couverture minimale** : 70% recommandé

### 7.2 Structure des tests
```
tests/
├── Unit/
│   └── Service/
│       └── UserServiceTest.php
├── Functional/
│   └── Controller/
│       └── UserControllerTest.php
└── bootstrap.php
```

### 7.3 Exemple de test unitaire
```php
namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testCreateUser(): void
    {
        // Arrange
        $dto = new CreateUserDto(/* ... */);

        // Act
        $user = $this->service->createUser($dto);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($dto->email, $user->getEmail());
    }
}
```

---

## 8. Gestion des événements

**Event System Symfony**
Pour les actions transversales (logging, notifications, etc.), utiliser le système d'événements Symfony :
- **Event Listeners** : Pour une logique simple
- **Event Subscribers** : Pour écouter plusieurs événements

```php
// Event
class UserCreatedEvent
{
    public function __construct(
        public readonly User $user
    ) {}
}

// Subscriber
class UserNotificationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'onUserCreated',
        ];
    }

    public function onUserCreated(UserCreatedEvent $event): void
    {
        // Envoyer email de bienvenue
    }
}
```

---

## 9. Bonnes pratiques Git

### 9.1 Workflow
- **Commits réguliers** : Au moins une fois par jour de travail
- **Push quotidien** : Pousser les changements sur le dépôt distant chaque jour
- **Backup automatique** : Le dépôt GitHub sert de backup

### 9.2 Structure des branches
```
main/master          → Production
  ↓
develop             → Développement (si Git Flow)
  ↓
feature/user-auth   → Nouvelles fonctionnalités
feature/payment
  ↓
bugfix/login-error  → Corrections de bugs
  ↓
hotfix/security-fix → Corrections urgentes
```

### 9.3 Messages de commit
**Format conventionnel** :
```
type(scope): description concise

Description détaillée si nécessaire

- Liste des changements
- Fix #123 (référence issue)
```

**Types de commit** :
- `feat` : Nouvelle fonctionnalité
- `fix` : Correction de bug
- `docs` : Documentation
- `style` : Formatage du code
- `refactor` : Refactoring
- `test` : Ajout de tests
- `chore` : Tâches diverses (dépendances, config)

**Exemples** :
```bash
git commit -m "feat(user): add registration with email validation"

git commit -m "fix(payment): resolve Stripe webhook signature validation
- Update signature verification logic
- Add better error handling
- Fix #42"

git commit -m "docs(api): update authentication endpoints documentation"
```

### 9.4 Pull Requests
**Règles** :
- Une PR par fonctionnalité/bugfix
- Revue de code obligatoire avant merge
- Tests passants requis
- Analyse de code (PHPStan, PHP CS Fixer) réussie

**Template de PR** :
```
## Description
[Description de la fonctionnalité/correction]

## Type de changement
- [ ] Nouvelle fonctionnalité
- [ ] Correction de bug
- [ ] Refactoring
- [ ] Documentation

## Checklist
- [ ] Code testé localement
- [ ] Tests unitaires/fonctionnels ajoutés
- [ ] PHPStan niveau 9 passé
- [ ] Documentation mise à jour
- [ ] Pas de conflits avec `main`

## Screenshots (si applicable)
[Captures d'écran]
```

### 9.5 Versionnement
- **Tags** : Utiliser le versionnement sémantique (semver)
- **Format** : `v{MAJOR}.{MINOR}.{PATCH}`

```bash
# Release majeure (breaking changes)
git tag v2.0.0

# Release mineure (nouvelles fonctionnalités)
git tag v1.1.0

# Release patch (corrections)
git tag v1.0.1

# Pusher les tags
git push --tags
```

---

## 10. Charte graphique

### 10.1 Palette de couleurs

**Couleur principale - Corail Vif**
- **Code HEX** : `#FF7070`
- **Utilisation** : Éléments d'interaction et d'accentuation visuelle
- **Applications** :
  - Bordures actives
  - Fonds de boutons primaires
  - Icônes d'illustration
  - Liens interactifs
  - États de survol

```css
.btn-primary {
    background-color: #FF7070;
    color: #FFFFFF; /* Obligatoire pour le contraste */
}
```

**Couleur complémentaire - Cyan Clair**
- **Code HEX** : `#70FFFF`
- **Utilisation** : Éléments secondaires, accents visuels, fonds de cartes
- **Applications** :
  - Bordures secondaires
  - Fonds de boutons secondaires
  - Accents graphiques
  - Survols alternatifs

```css
.btn-secondary {
    background-color: #70FFFF;
}
```

### 10.2 Couleurs de texte
**Règles strictes**
- Texte sur fond Corail : Blanc pur `#FFFFFF` (obligatoire pour le contraste)
- Texte sur fond clair : Gris anthracite `#2B2B2B` (équivalent Tailwind gray-800)
- ⛔ **INTERDIT** : Noir pur (`#000000`) sur tout le projet

```css
/* ✅ Bon */
.text-primary {
    color: #2B2B2B;
}

/* ❌ Interdit */
.text-wrong {
    color: #000000;
}
```

### 10.3 Fonds et arrière-plans
- **Fond principal** : Blanc pur `#FFFFFF`
- **Fond secondaire** : Gris très clair `#F8FAFC`
- **Objectif** : Sobriété pour mettre en valeur les accents Corail

```css
body {
    background-color: #FFFFFF;
}

.card {
    background-color: #F8FAFC;
}
```

---

## 11. Documentation & Références

### 11.1 Documentation technique
L'IA doit se référer prioritairement à ces sources officielles :
- **Symfony** : https://symfony.com/doc
- **Bootstrap 5.3** : https://getbootstrap.com/docs/5.3/getting-started/introduction/
- **Symfony UX** : https://symfony.com/bundles/ux-twig-component/current/index.html
- **Stripe** : https://docs.stripe.com/

### 11.2 Clés API & Services

**Stripe (Environnement de test)**
```bash
# .env.local
STRIPE_PUBLIC_KEY=your_stripe_public_key_here
STRIPE_SECRET_KEY=your_stripe_secret_key_here
```

**Mailjet**
```bash
# .env.local
MAILJET_API_KEY=your_mailjet_api_key_here
MAILJET_SECRET_KEY=your_mailjet_secret_key_here
```
Documentation : https://www.mailjet.com/fr/

---

## 12. Nouveautés technologiques

### 12.1 Symfony 8
**Nouvelles fonctionnalités à utiliser**

**Form Flow**
- Pour les formulaires multi-étapes
- Disponible via `symfony/form-flow`
```bash
composer require symfony/form-flow
```

**Composants stables**
- **JsonStreamer** : Streaming de données JSON
- **ObjectMapper** : Mapping automatique d'objets
- **JsonPath** : Requêtes avancées sur JSON

**Améliorations des composants existants**
- **HttpClient** : Meilleures performances
- **Mailer** : Nouvelles fonctionnalités
- **Messenger** : Gestion améliorée des messages
- **Form** : Nouveaux types de champs
- **Validator** : Nouvelles contraintes
- **Workflow** : Simplification de la configuration

### 12.2 PHP 8.5

**Property Hooks**
Nouvelle syntaxe pour les accesseurs/mutateurs
```php
class User
{
    // ✅ PHP 8.5 : Property Hooks
    public string $email {
        get => strtolower($this->email);
        set (string $value) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Invalid email');
            }
            $this->email = $value;
        }
    }
}
```

**Typed Constants**
Typage des constantes de classe
```php
class Status
{
    public const string ACTIVE = 'active';
    public const string INACTIVE = 'inactive';
    public const int MAX_RETRIES = 3;
}
```

**Nouvelles fonctions de tableau**
```php
// array_find() : Trouver le premier élément
$user = array_find($users, fn($u) => $u->isActive());

// array_find_key() : Trouver la clé
$key = array_find_key($users, fn($u) => $u->id === 42);

// array_any() : Au moins un élément correspond
$hasActive = array_any($users, fn($u) => $u->isActive());

// array_all() : Tous les éléments correspondent
$allActive = array_all($users, fn($u) => $u->isActive());
```

**JIT amélioré**
- Meilleures performances pour les applications CPU-intensive
- Optimisations automatiques

**Sécurité renforcée**
- Améliorations des fonctions cryptographiques
- Meilleures pratiques par défaut
