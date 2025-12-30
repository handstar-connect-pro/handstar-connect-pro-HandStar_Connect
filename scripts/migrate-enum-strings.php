<?php

declare(strict_types=1);

/**
 * Script de migration pour détecter et remplacer les chaînes en dur
 * par des constantes d'énumération dans le code source.
 *
 * Usage: php scripts/migrate-enum-strings.php [--dry-run] [--fix]
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\EnumValidationService;

class EnumStringMigrator
{
    private array $enumPatterns = [
        // PersonGender
        '/(["\'])(MALE|FEMALE)\1/' => [
            'enum' => \App\Enums\PersonGender::class,
            'replacement' => '\\App\\Enums\\PersonGender::$2->value',
        ],

        // PlayerLaterality
        '/(["\'])(RIGHT_HANDED|LEFT_HANDED|AMBIDEXTROUS)\1/' => [
            'enum' => \App\Enums\PlayerLaterality::class,
            'replacement' => '\\App\\Enums\\PlayerLaterality::$2->value',
        ],

        // PlayerPosition
        '/(["\'])(GOALKEEPER|LEFT_WING|LEFT_BACK|CENTER_BACK|RIGHT_BACK|RIGHT_WING|PIVOT)\1/' => [
            'enum' => \App\Enums\PlayerPosition::class,
            'replacement' => '\\App\\Enums\\PlayerPosition::$2->value',
        ],

        // LevelDivision
        '/(["\'])(LIQUI_MOLY_STARLIGUE|PROLIGUE|LIGUE_BUTAGAZ_ENERGIE|D2_FEMININE|NATIONALE_1_ELITE|NATIONALE_1|NATIONALE_2|NATIONALE_3|PRENATIONAL|EXCELLENCE_REGIONALE|HONNEUR_REGIONALE|DEPARTEMENTAL)\1/' => [
            'enum' => \App\Enums\LevelDivision::class,
            'replacement' => '\\App\\Enums\\LevelDivision::$2->value',
        ],

        // UserProfil
        '/(["\'])(PLAYER|COACH|REFEREE|TECHNICAL_DIRECTOR|VIDEO_ANALYST|PHYSICAL_TRAINER|PHYSIO_THERAPIST|MENTAL_TRAINER)\1/' => [
            'enum' => \App\Enums\UserProfil::class,
            'replacement' => '\\App\\Enums\\UserProfil::$2->value',
        ],

        // AnnouncementType
        '/(["\'])(PLAYER_SEARCH|COACH_SEARCH|CLUB_SEARCH|TRAINING_SESSION|TOURNAMENT|FRIENDLY_MATCH)\1/' => [
            'enum' => \App\Enums\AnnouncementType::class,
            'replacement' => '\\App\\Enums\\AnnouncementType::$2->value',
        ],

        // AnnouncementStatus
        '/(["\'])(PENDING|PUBLISHED|CLOSED|CANCELLED)\1/' => [
            'enum' => \App\Enums\AnnouncementStatus::class,
            'replacement' => '\\App\\Enums\\AnnouncementStatus::$2->value',
        ],

        // ResponseStatus
        '/(["\'])(PENDING|ACCEPTED|REJECTED|CANCELLED)\1/' => [
            'enum' => \App\Enums\ResponseStatus::class,
            'replacement' => '\\App\\Enums\\ResponseStatus::$2->value',
        ],
    ];

    private bool $dryRun = true;
    private bool $fix = false;
    private array $stats = [
        'files_scanned' => 0,
        'files_modified' => 0,
        'replacements_found' => 0,
        'replacements_made' => 0,
        'errors' => 0,
    ];

    public function __construct(bool $dryRun = true, bool $fix = false)
    {
        $this->dryRun = $dryRun;
        $this->fix = $fix;
    }

    public function run(): void
    {
        echo "=== Migration des chaînes d'énumération ===\n";
        echo "Mode: " . ($this->dryRun ? "DRY RUN (simulation)" : "EXÉCUTION") . "\n";
        echo "Fix: " . ($this->fix ? "ACTIF (remplacement)" : "INACTIF (détection seulement)") . "\n\n";

        $this->scanDirectory(__DIR__ . '/../src');
        $this->scanDirectory(__DIR__ . '/../tests');

        $this->printStats();
    }

    private function scanDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            echo "⚠️  Répertoire non trouvé: $directory\n";
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->processFile($file->getPathname());
            }
        }
    }

    private function processFile(string $filepath): void
    {
        $this->stats['files_scanned']++;

        $content = file_get_contents($filepath);
        if ($content === false) {
            $this->stats['errors']++;
            echo "❌ Erreur de lecture: $filepath\n";
            return;
        }

        $originalContent = $content;
        $replacements = [];

        foreach ($this->enumPatterns as $pattern => $config) {
            $matches = [];
            if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as $index => $match) {
                    $fullMatch = $match[0];
                    $value = $matches[2][$index][0] ?? null;

                    if ($value === null) {
                        continue;
                    }

                    // Vérifier que c'est bien une valeur d'énumération valide
                    if (EnumValidationService::validateEnum($config['enum'], $value)) {
                        $replacement = str_replace('$2', $value, $config['replacement']);

                        $replacements[] = [
                            'search' => $fullMatch,
                            'replace' => $replacement,
                            'value' => $value,
                            'enum' => $config['enum'],
                        ];

                        $this->stats['replacements_found']++;
                    }
                }
            }
        }

        if (empty($replacements)) {
            return;
        }

        echo "📄 $filepath\n";
        echo "   Trouvé " . count($replacements) . " chaîne(s) d'énumération\n";

        foreach ($replacements as $replacement) {
            echo "   • '{$replacement['search']}' → {$replacement['replace']}\n";

            if ($this->fix && !$this->dryRun) {
                $content = str_replace($replacement['search'], $replacement['replace'], $content);
                $this->stats['replacements_made']++;
            }
        }

        if ($this->fix && !$this->dryRun && $content !== $originalContent) {
            if (file_put_contents($filepath, $content) !== false) {
                $this->stats['files_modified']++;
                echo "   ✅ Fichier modifié\n";
            } else {
                $this->stats['errors']++;
                echo "   ❌ Erreur d'écriture\n";
            }
        }

        echo "\n";
    }

    private function printStats(): void
    {
        echo "\n=== Statistiques ===\n";
        echo "Fichiers scannés: {$this->stats['files_scanned']}\n";
        echo "Fichiers modifiés: {$this->stats['files_modified']}\n";
        echo "Remplacements trouvés: {$this->stats['replacements_found']}\n";
        echo "Remplacements effectués: {$this->stats['replacements_made']}\n";
        echo "Erreurs: {$this->stats['errors']}\n";

        if ($this->dryRun) {
            echo "\n⚠️  MODE SIMULATION - Aucun fichier n'a été modifié\n";
            echo "Pour appliquer les changements, exécutez:\n";
            echo "  php scripts/migrate-enum-strings.php --fix\n";
        } elseif (!$this->fix) {
            echo "\n⚠️  MODE DÉTECTION SEULEMENT - Aucun fichier n'a été modifié\n";
            echo "Pour appliquer les changements, exécutez:\n";
            echo "  php scripts/migrate-enum-strings.php --fix\n";
        } else {
            echo "\n✅ MIGRATION TERMINÉE - Les fichiers ont été modifiés\n";
        }
    }

    public function validateEnumValues(): void
    {
        echo "\n=== Validation des valeurs d'énumération ===\n";

        $invalidFiles = [];

        $this->scanForInvalidValues(__DIR__ . '/../src', $invalidFiles);
        $this->scanForInvalidValues(__DIR__ . '/../tests', $invalidFiles);

        if (empty($invalidFiles)) {
            echo "✅ Toutes les valeurs d'énumération sont valides\n";
        } else {
            echo "❌ Valeurs d'énumération invalides trouvées:\n";
            foreach ($invalidFiles as $file => $invalidValues) {
                echo "  📄 $file\n";
                foreach ($invalidValues as $value => $line) {
                    echo "    • Ligne $line: '$value'\n";
                }
            }
        }
    }

    private function scanForInvalidValues(string $directory, array &$invalidFiles): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->checkFileForInvalidValues($file->getPathname(), $invalidFiles);
            }
        }
    }

    private function checkFileForInvalidValues(string $filepath, array &$invalidFiles): void
    {
        $lines = file($filepath, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $lineNumber => $line) {
            foreach ($this->enumPatterns as $pattern => $config) {
                $matches = [];
                if (preg_match_all($pattern, $line, $matches)) {
                    foreach ($matches[2] as $value) {
                        if (!EnumValidationService::validateEnum($config['enum'], $value)) {
                            $invalidFiles[$filepath][$value] = $lineNumber + 1;
                        }
                    }
                }
            }
        }
    }
}

// Exécution du script
$options = getopt('', ['dry-run', 'fix', 'validate']);
$dryRun = isset($options['dry-run']);
$fix = isset($options['fix']);
$validate = isset($options['validate']);

$migrator = new EnumStringMigrator($dryRun, $fix);

if ($validate) {
    $migrator->validateEnumValues();
} else {
    $migrator->run();
}
