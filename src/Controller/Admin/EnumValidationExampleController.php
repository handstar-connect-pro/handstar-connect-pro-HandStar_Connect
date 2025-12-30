<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Services\EnumValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Exemple de contrôleur démontrant l'utilisation de la validation des énumérations.
 *
 * Ce contrôleur montre comment utiliser EnumValidationService pour valider
 * les valeurs d'énumération plutôt que d'utiliser des chaînes en dur.
 */
#[Route('/admin/examples/enum-validation')]
class EnumValidationExampleController extends AbstractController
{
    /**
     * Exemple 1: Validation d'une valeur d'énumération spécifique.
     */
    #[Route('/validate-person-gender', name: 'admin_example_validate_person_gender', methods: ['GET'])]
    public function validatePersonGenderExample(Request $request): JsonResponse
    {
        $gender = $request->query->get('gender', 'MALE');

        // AVANT: Utilisation de chaîne en dur
        // $isValid = in_array($gender, ['MALE', 'FEMALE']);

        // APRÈS: Utilisation de l'énumération
        $isValid = EnumValidationService::validatePersonGender($gender);

        return $this->json([
            'value' => $gender,
            'is_valid' => $isValid,
            'message' => $isValid
                ? "La valeur '$gender' est valide pour PersonGender."
                : "La valeur '$gender' n'est pas valide pour PersonGender.",
            'valid_values' => EnumValidationService::getValidValues(\App\Enums\PersonGender::class),
        ]);
    }

    /**
     * Exemple 2: Validation avec récupération de l'instance d'énumération.
     */
    #[Route('/get-validated-enum', name: 'admin_example_get_validated_enum', methods: ['GET'])]
    public function getValidatedEnumExample(Request $request): JsonResponse
    {
        $value = $request->query->get('value', 'RIGHT_HANDED');
        $enumClass = $request->query->get('enum_class', \App\Enums\PlayerLaterality::class);

        try {
            $enum = EnumValidationService::getValidatedEnum($enumClass, $value);

            return $this->json([
                'success' => true,
                'value' => $value,
                'enum_class' => $enumClass,
                'enum_instance' => [
                    'name' => $enum->name,
                    'value' => $enum->value,
                ],
                'message' => "La valeur '$value' est valide pour $enumClass.",
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'success' => false,
                'value' => $value,
                'enum_class' => $enumClass,
                'error' => $e->getMessage(),
                'valid_values' => EnumValidationService::getValidValues($enumClass),
            ], 400);
        }
    }

    /**
     * Exemple 3: Validation dans un contexte de création d'entité.
     */
    #[Route('/create-player-example', name: 'admin_example_create_player', methods: ['POST'])]
    public function createPlayerExample(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Données d'exemple avec validation
        $playerData = [
            'gender' => $data['gender'] ?? 'MALE',
            'handedness' => $data['handedness'] ?? 'RIGHT_HANDED',
            'position' => $data['position'] ?? 'GOALKEEPER',
            'division' => $data['division'] ?? 'NATIONALE_1',
        ];

        $errors = [];

        // Validation de chaque champ avec les énumérations
        if (!EnumValidationService::validatePersonGender($playerData['gender'])) {
            $errors[] = EnumValidationService::getErrorMessage(
                \App\Enums\PersonGender::class,
                $playerData['gender']
            );
        }

        if (!EnumValidationService::validatePlayerLaterality($playerData['handedness'])) {
            $errors[] = EnumValidationService::getErrorMessage(
                \App\Enums\PlayerLaterality::class,
                $playerData['handedness']
            );
        }

        if (!EnumValidationService::validatePlayerPosition($playerData['position'])) {
            $errors[] = EnumValidationService::getErrorMessage(
                \App\Enums\PlayerPosition::class,
                $playerData['position']
            );
        }

        if (!EnumValidationService::validateLevelDivision($playerData['division'])) {
            $errors[] = EnumValidationService::getErrorMessage(
                \App\Enums\LevelDivision::class,
                $playerData['division']
            );
        }

        if (!empty($errors)) {
            return $this->json([
                'success' => false,
                'errors' => $errors,
                'player_data' => $playerData,
            ], 400);
        }

        // Si toutes les validations passent, on peut créer l'entité
        // Note: Ceci est un exemple, on ne crée pas réellement l'entité
        return $this->json([
            'success' => true,
            'message' => 'Toutes les validations d\'énumération ont réussi.',
            'player_data' => $playerData,
            'validated_enums' => [
                'gender' => EnumValidationService::getValidatedEnum(
                    \App\Enums\PersonGender::class,
                    $playerData['gender']
                )->value,
                'handedness' => EnumValidationService::getValidatedEnum(
                    \App\Enums\PlayerLaterality::class,
                    $playerData['handedness']
                )->value,
                'position' => EnumValidationService::getValidatedEnum(
                    \App\Enums\PlayerPosition::class,
                    $playerData['position']
                )->value,
                'division' => EnumValidationService::getValidatedEnum(
                    \App\Enums\LevelDivision::class,
                    $playerData['division']
                )->value,
            ],
        ]);
    }

    /**
     * Exemple 4: Liste de toutes les valeurs valides pour chaque énumération.
     */
    #[Route('/all-valid-values', name: 'admin_example_all_valid_values', methods: ['GET'])]
    public function getAllValidValues(): JsonResponse
    {
        $enums = [
            'PersonGender' => \App\Enums\PersonGender::class,
            'PlayerLaterality' => \App\Enums\PlayerLaterality::class,
            'PlayerPosition' => \App\Enums\PlayerPosition::class,
            'LevelDivision' => \App\Enums\LevelDivision::class,
            'UserProfil' => \App\Enums\UserProfil::class,
            'AnnouncementType' => \App\Enums\AnnouncementType::class,
            'AnnouncementStatus' => \App\Enums\AnnouncementStatus::class,
            'ResponseStatus' => \App\Enums\ResponseStatus::class,
        ];

        $result = [];
        foreach ($enums as $name => $enumClass) {
            $result[$name] = [
                'class' => $enumClass,
                'valid_values' => EnumValidationService::getValidValues($enumClass),
                'default_value' => EnumValidationService::getDefaultValue($enumClass),
            ];
        }

        return $this->json([
            'enums' => $result,
            'message' => 'Liste de toutes les valeurs valides pour chaque énumération.',
        ]);
    }

    /**
     * Exemple 5: Utilisation dans un formulaire ou un DTO.
     */
    #[Route('/dto-example', name: 'admin_example_dto', methods: ['POST'])]
    public function dtoExample(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Utilisation du DTO
        $result = PlayerCreationDtoExample::createValidated($data);

        if (is_array($result)) {
            return $this->json([
                'success' => false,
                'message' => 'Erreurs de validation dans le DTO.',
                'errors' => $result,
            ], 400);
        }

        return $this->json([
            'success' => true,
            'message' => 'DTO validé avec succès.',
            'dto' => [
                'gender' => $result->gender,
                'handedness' => $result->handedness,
                'position' => $result->position,
                'division' => $result->division,
            ],
        ]);
    }
}

// DTO exemple défini en dehors de la classe du contrôleur
class PlayerCreationDtoExample
{
    public function __construct(
        public string $gender,
        public string $handedness,
        public string $position,
        public string $division
    ) {}

    /**
     * Valide le DTO en utilisant EnumValidationService.
     */
    public function validate(): array
    {
        $errors = [];

        $checks = [
            ['gender', \App\Enums\PersonGender::class],
            ['handedness', \App\Enums\PlayerLaterality::class],
            ['position', \App\Enums\PlayerPosition::class],
            ['division', \App\Enums\LevelDivision::class],
        ];

        foreach ($checks as [$property, $enumClass]) {
            if (!EnumValidationService::validateEnum($enumClass, $this->$property)) {
                $errors[] = EnumValidationService::getErrorMessage(
                    $enumClass,
                    $this->$property
                );
            }
        }

        return $errors;
    }

    /**
     * Crée une instance validée du DTO.
     */
    public static function createValidated(array $data): self|array
    {
        $dto = new self(
            $data['gender'] ?? 'MALE',
            $data['handedness'] ?? 'RIGHT_HANDED',
            $data['position'] ?? 'GOALKEEPER',
            $data['division'] ?? 'NATIONALE_1'
        );

        $errors = $dto->validate();
        if (!empty($errors)) {
            return $errors;
        }

        return $dto;
    }
}
