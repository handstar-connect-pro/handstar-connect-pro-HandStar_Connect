<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Enums\UserProfil;
use App\Services\AccessControlService;
use PHPUnit\Framework\TestCase;

class AccessControlServiceTest extends TestCase
{
    private AccessControlService $accessControlService;

    protected function setUp(): void
    {
        $this->accessControlService = new AccessControlService();
    }

    public function testCanRespond(): void
    {
        // Test: Un club peut répondre à un joueur
        $this->assertTrue(
            $this->accessControlService->canRespond(UserProfil::CLUB, UserProfil::PLAYER)
        );

        // Test: Un joueur peut répondre à un club
        $this->assertTrue(
            $this->accessControlService->canRespond(UserProfil::PLAYER, UserProfil::CLUB)
        );

        // Test: Un joueur ne peut pas répondre à un autre joueur
        $this->assertFalse(
            $this->accessControlService->canRespond(UserProfil::PLAYER, UserProfil::PLAYER)
        );

        // Test: Un entraîneur peut répondre à tous (corrigé)
        $this->assertTrue(
            $this->accessControlService->canRespond(UserProfil::COACH, UserProfil::PLAYER)
        );
        $this->assertTrue(
            $this->accessControlService->canRespond(UserProfil::COACH, UserProfil::CLUB)
        );
    }

    public function testCanSeeAnnouncements(): void
    {
        // Test: Un club peut voir les annonces d'un joueur
        $this->assertTrue(
            $this->accessControlService->canSeeAnnouncements(UserProfil::CLUB, UserProfil::PLAYER)
        );

        // Test: Un joueur peut voir les annonces d'un club
        $this->assertTrue(
            $this->accessControlService->canSeeAnnouncements(UserProfil::PLAYER, UserProfil::CLUB)
        );

        // Test: Un joueur peut voir les annonces d'un entraîneur (corrigé)
        $this->assertTrue(
            $this->accessControlService->canSeeAnnouncements(UserProfil::PLAYER, UserProfil::COACH)
        );
    }

    public function testGetAllowedResponseTargets(): void
    {
        $clubTargets = $this->accessControlService->getAllowedResponseTargets(UserProfil::CLUB);
        $playerTargets = $this->accessControlService->getAllowedResponseTargets(UserProfil::PLAYER);
        $coachTargets = $this->accessControlService->getAllowedResponseTargets(UserProfil::COACH);

        // Club peut répondre à tous les profils
        $this->assertCount(count(UserProfil::cases()), $clubTargets);

        // Joueur peut répondre à plusieurs profils mais pas à tous
        $this->assertGreaterThan(0, count($playerTargets));
        $this->assertLessThan(count(UserProfil::cases()), count($playerTargets));

        // Entraîneur peut répondre à tous (corrigé)
        $this->assertCount(count(UserProfil::cases()), $coachTargets);
    }

    public function testGetAllowedViewers(): void
    {
        $clubViewers = $this->accessControlService->getAllowedViewers(UserProfil::CLUB);
        $playerViewers = $this->accessControlService->getAllowedViewers(UserProfil::PLAYER);

        // Les annonces de club sont visibles par tous
        $this->assertCount(count(UserProfil::cases()), $clubViewers);

        // Les annonces de joueur sont visibles par plusieurs profils mais pas tous
        $this->assertGreaterThan(0, count($playerViewers));
        $this->assertLessThan(count(UserProfil::cases()), count($playerViewers));
    }

    public function testIsRelationshipSymmetric(): void
    {
        // Test: Relation club-joueur devrait être symétrique
        $this->assertTrue(
            $this->accessControlService->isRelationshipSymmetric(UserProfil::CLUB, UserProfil::PLAYER)
        );

        // Test: Relation joueur-club devrait être symétrique
        $this->assertTrue(
            $this->accessControlService->isRelationshipSymmetric(UserProfil::PLAYER, UserProfil::CLUB)
        );
    }

    public function testValidateRules(): void
    {
        $inconsistencies = $this->accessControlService->validateRules();

        // Après les corrections, il ne devrait plus y avoir d'incohérences
        $this->assertIsArray($inconsistencies);

        // Afficher les incohérences pour le débogage si nécessaire
        if (count($inconsistencies) > 0) {
            foreach ($inconsistencies as $inconsistency) {
                echo "\nIncohérence détectée: $inconsistency";
            }
        }

        // Avec les corrections appliquées, on s'attend à 0 incohérence
        $this->assertCount(0, $inconsistencies, 'Il reste des incohérences dans les règles d\'accès');
    }

    public function testHasFullPermissions(): void
    {
        // Club a des permissions complètes
        $this->assertTrue($this->accessControlService->hasFullPermissions(UserProfil::CLUB));

        // Joueur n'a pas de permissions complètes
        $this->assertFalse($this->accessControlService->hasFullPermissions(UserProfil::PLAYER));

        // Entraîneur a des permissions complètes (corrigé)
        $this->assertTrue($this->accessControlService->hasFullPermissions(UserProfil::COACH));
    }

    public function testGetProfileRulesDescription(): void
    {
        $clubRules = $this->accessControlService->getProfileRulesDescription(UserProfil::CLUB);
        $playerRules = $this->accessControlService->getProfileRulesDescription(UserProfil::PLAYER);

        $this->assertArrayHasKey('profile', $clubRules);
        $this->assertArrayHasKey('can_respond_to', $clubRules);
        $this->assertArrayHasKey('can_be_seen_by', $clubRules);
        $this->assertArrayHasKey('has_full_permissions', $clubRules);

        $this->assertEquals('Club', $clubRules['profile']);
        $this->assertTrue($clubRules['has_full_permissions']);
    }

    public function testGetAllRules(): void
    {
        $allRules = $this->accessControlService->getAllRules();

        $this->assertIsArray($allRules);
        $this->assertCount(count(UserProfil::cases()), $allRules);

        foreach (UserProfil::cases() as $profil) {
            $this->assertArrayHasKey($profil->value, $allRules);
        }
    }

    public function testCanProfilesInteract(): void
    {
        // Test: Club et joueur peuvent interagir
        $this->assertTrue(
            $this->accessControlService->canProfilesInteract([
                UserProfil::CLUB,
                UserProfil::PLAYER
            ])
        );

        // Test: Club, joueur et entraîneur peuvent interagir
        $this->assertTrue(
            $this->accessControlService->canProfilesInteract([
                UserProfil::CLUB,
                UserProfil::PLAYER,
                UserProfil::COACH
            ])
        );

        // Test: Un seul profil peut "interagir" avec lui-même
        $this->assertTrue(
            $this->accessControlService->canProfilesInteract([
                UserProfil::PLAYER
            ])
        );
    }
}
