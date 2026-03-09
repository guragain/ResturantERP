<?php

namespace Tests\Feature\Unit\Services;

use App\DTOs\LoginResultDTO;
use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegistrationDTO;
use App\Models\User;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_it_hashes_password_and_calls_repository_to_register_user(): void
    {
        // 1. Arrange
        $dto = new UserRegistrationDTO(
            name: 'John Doe',
            email: 'john@example.com',
            password: 'secret-password'
        );

        $fakeUser  = new User(['name' => 'John Doe', 'email' => 'john@example.com']);
        $fakeToken = 'fake-reg-token-123';
        $fakeResult = new LoginResultDTO($fakeUser, $fakeToken);

        // 2. Mock the Interface
        $this->mock(AuthRepositoryInterface::class, function (MockInterface $mock) use ($fakeResult) {
            $mock->shouldReceive('register')
                ->once()
                ->with(Mockery::on(function ($data) {
                    return $data['email'] === 'john@example.com' &&
                        Hash::check('secret-password', $data['password']);
                }))
                ->andReturn($fakeResult);
        });

        // 3. Act
        $service = app(AuthService::class);
        $result = $service->register($dto);

        // 4. Assert
        $this->assertInstanceOf(LoginResultDTO::class, $result);
        $this->assertEquals('John Doe', $result->user->name);
        $this->assertEquals($fakeToken, $result->token);
    }

    public function test_login_returns_login_result_dto_on_valid_credentials(): void
    {
        // 1. Arrange
        $dto = new UserLoginDTO(
            username: 'john@example.com',
            password: 'secret-password'
        );

        $fakeUser   = new User(['name' => 'John Doe', 'email' => 'john@example.com']);
        $fakeToken  = 'fake-sanctum-token-123';
        $fakeResult = new LoginResultDTO($fakeUser, $fakeToken);

        // 2. Mock the repository
        $this->mock(AuthRepositoryInterface::class, function (MockInterface $mock) use ($fakeResult) {
            $mock->shouldReceive('login')
                ->once()
                ->with(Mockery::on(function ($credentials) {
                    return $credentials['username'] === 'john@example.com' &&
                        $credentials['password'] === 'secret-password';
                }))
                ->andReturn($fakeResult);
        });

        // 3. Act
        $service = app(AuthService::class);
        $result  = $service->login($dto);

        // 4. Assert
        $this->assertInstanceOf(LoginResultDTO::class, $result);
        $this->assertEquals('john@example.com', $result->user->email);
        $this->assertEquals($fakeToken, $result->token);
    }

    public function test_login_throws_exception_on_invalid_credentials(): void
    {
        // 1. Arrange
        $dto = new UserLoginDTO(
            username: 'wrong@example.com',
            password: 'wrong-password'
        );

        // 2. Mock the repository to throw an exception
        $this->mock(AuthRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('login')
                ->once()
                ->andThrow(new \Exception('Invalid credentials', 401));
        });

        // 3. Assert exception is thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid credentials');
        $this->expectExceptionCode(401);

        // 4. Act
        $service = app(AuthService::class);
        $service->login($dto);
    }
}
