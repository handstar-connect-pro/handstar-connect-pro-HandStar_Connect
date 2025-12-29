<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Dto\CreateUserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->userService = new UserService(
            $this->userRepository,
            $this->passwordHasher,
            $this->validator
        );
    }

    public function testCreateUserFromDto(): void
    {
        // Arrange
        $dto = new CreateUserDto(
            email: 'test@example.com',
            password: 'Password123!',
            firstName: 'John',
            lastName: 'Doe',
            role: 'ROLE_USER'
        );

        $expectedUser = new User();
        $expectedUser->setEmail('test@example.com');
        $expectedUser->setPassword('hashed_password');
        $expectedUser->setRoles(['ROLE_USER']);

        // Mock validation
        $this->validator->method('validate')->willReturn([]);

        // Mock password hashing
        $this->passwordHasher
            ->method('hashPassword')
            ->willReturn('hashed_password');

        // Mock repository
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class), true);

        $this->userRepository
            ->method('findOneBy')
            ->willReturn(null);

        // Act
        $user = $this->userService->createUserFromDto($dto);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertEquals(['ROLE_USER', 'ROLE_USER'], $user->getRoles()); // ROLE_USER est ajouté par défaut
    }

    public function testIsEmailTakenReturnsTrueWhenEmailExists(): void
    {
        // Arrange
        $existingUser = new User();
        $existingUser->setEmail('existing@example.com');

        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => 'existing@example.com'])
            ->willReturn($existingUser);

        // Act
        $result = $this->userService->isEmailTaken('existing@example.com');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsEmailTakenReturnsFalseWhenEmailDoesNotExist(): void
    {
        // Arrange
        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => 'nonexistent@example.com'])
            ->willReturn(null);

        // Act
        $result = $this->userService->isEmailTaken('nonexistent@example.com');

        // Assert
        $this->assertFalse($result);
    }

    public function testFindUserByEmailReturnsUserWhenExists(): void
    {
        // Arrange
        $expectedUser = new User();
        $expectedUser->setEmail('test@example.com');

        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => 'test@example.com'])
            ->willReturn($expectedUser);

        // Act
        $user = $this->userService->findUserByEmail('test@example.com');

        // Assert
        $this->assertSame($expectedUser, $user);
    }

    public function testFindUserByEmailReturnsNullWhenNotExists(): void
    {
        // Arrange
        $this->userRepository
            ->method('findOneBy')
            ->with(['email' => 'nonexistent@example.com'])
            ->willReturn(null);

        // Act
        $user = $this->userService->findUserByEmail('nonexistent@example.com');

        // Assert
        $this->assertNull($user);
    }
}
