<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Entity\Announcement;
use App\Enums\AnnouncementStatus;
use App\Services\AnnouncementManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class AnnouncementManagerTest extends TestCase
{
    private AnnouncementManager $announcementManager;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->announcementManager = new AnnouncementManager($this->entityManager);
    }

    public function testCreateAnnouncementSetsDefaultExpirationDate(): void
    {
        $announcement = new Announcement();
        $announcement->setOfferTitle('Test Announcement');

        // Mock EntityManager behavior
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($announcement));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->announcementManager->createAnnouncement($announcement);

        $this->assertNotNull($announcement->getExpiresAt());
        $this->assertEquals(AnnouncementStatus::ACTIVE, $announcement->getOfferStatus());
    }

    public function testUpdateAnnouncementSetsUpdatedAt(): void
    {
        $announcement = new Announcement();
        $announcement->setOfferTitle('Test Announcement');

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->announcementManager->updateAnnouncement($announcement);

        $this->assertNotNull($announcement->getUpdatedAt());
    }

    public function testCloseAnnouncementSetsStatusToClosed(): void
    {
        $announcement = new Announcement();
        $announcement->setOfferTitle('Test Announcement');
        $announcement->setOfferStatus(AnnouncementStatus::ACTIVE);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->announcementManager->closeAnnouncement($announcement);

        $this->assertEquals(AnnouncementStatus::CLOSED, $announcement->getOfferStatus());
        $this->assertNotNull($announcement->getUpdatedAt());
    }

    public function testIsAnnouncementExpired(): void
    {
        $announcement = new Announcement();

        // Test avec une date d'expiration passée
        $pastDate = new \DateTimeImmutable('-1 day');
        $announcement->setExpiresAt($pastDate);

        $this->assertTrue($this->announcementManager->isAnnouncementExpired($announcement));

        // Test avec une date d'expiration future
        $futureDate = new \DateTimeImmutable('+1 day');
        $announcement->setExpiresAt($futureDate);

        $this->assertFalse($this->announcementManager->isAnnouncementExpired($announcement));
    }

    public function testIsAnnouncementActive(): void
    {
        $announcement = new Announcement();

        // Test avec statut ACTIVE
        $announcement->setOfferStatus(AnnouncementStatus::ACTIVE);
        $this->assertTrue($this->announcementManager->isAnnouncementActive($announcement));

        // Test avec statut CLOSED
        $announcement->setOfferStatus(AnnouncementStatus::CLOSED);
        $this->assertFalse($this->announcementManager->isAnnouncementActive($announcement));
    }

    public function testNeedsRenewal(): void
    {
        $announcement = new Announcement();

        // Test sans date d'expiration
        $this->assertFalse($this->announcementManager->needsRenewal($announcement));

        // Test avec date d'expiration dans plus de 7 jours
        $futureDate = new \DateTimeImmutable('+10 days');
        $announcement->setExpiresAt($futureDate);
        $this->assertFalse($this->announcementManager->needsRenewal($announcement));

        // Test avec date d'expiration dans moins de 7 jours
        $nearFutureDate = new \DateTimeImmutable('+5 days');
        $announcement->setExpiresAt($nearFutureDate);
        $this->assertTrue($this->announcementManager->needsRenewal($announcement));

        // Test avec date d'expiration passée
        $pastDate = new \DateTimeImmutable('-1 day');
        $announcement->setExpiresAt($pastDate);
        $this->assertTrue($this->announcementManager->needsRenewal($announcement));
    }

    public function testGetDefaultExpirationDate(): void
    {
        $defaultDate = $this->announcementManager->getDefaultExpirationDate();

        $this->assertInstanceOf(\DateTimeImmutable::class, $defaultDate);

        // Vérifier que la date est dans environ 90 jours
        $expectedDate = new \DateTimeImmutable('+90 days');
        $difference = $expectedDate->diff($defaultDate)->days;

        $this->assertLessThan(2, abs($difference)); // Tolérance de 2 jours
    }
}
