<?php

namespace Tests\Feature\Unit\Services;

use App\Contracts\Interfaces\IAuthRepo;
use App\DTOs\UserRegistrationDTO;
use App\Models\User;
use App\Services\Implementation\AuthService;
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

        // 2. Mock the Interface
        // Ensure the string matches your actual Interface namespace exactly
        $this->mock(IAuthRepo::class, function (MockInterface $mock) {
            $mock->shouldReceive('register') // Make sure this matches the method name in IAuthRepo
            ->once()
                ->with(Mockery::on(function ($data) {
                    // Verify logic
                    return $data['email'] === 'john@example.com' &&
                        Hash::check('secret-password', $data['password']);
                }))
                ->andReturn(new User(['name' => 'John Doe', 'email' => 'john@example.com']));
        });

        // 3. Act
        // Resolve from the container to ensure the Mock we just made is injected
        $service = app(AuthService::class);
        $result = $service->register($dto);

        // 4. Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('John Doe', $result->name);
    }
}
