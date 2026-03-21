<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\DTOs\UserDTO;
use App\DTOs\AuthResponseDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(array $data): AuthResponseDTO
    {
        // Set username if not provided (e.g. from email prefix)
        if (empty($data['user_name'])) {
            $data['user_name'] = explode('@', $data['email'])[0] . rand(1000, 9999);
        }

        // Create user
        $user = $this->userRepository->create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password']
        ]);

        // Log the user in (creates session for SPA Auth)
        Auth::login($user);

        // Regenerate session for safety against session fixation
        request()->session()->regenerate();

        return new AuthResponseDTO(
            user: UserDTO::fromModel($user),
            message: 'Registration successful'
        );
    }

    public function login(array $credentials): AuthResponseDTO
    {
        // Support login by email or username
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
        
        $user = $field === 'email' 
            ? $this->userRepository->findByEmail($credentials['email']) 
            : $this->userRepository->findByUsername($credentials['email']);

        // Verify credentials
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        if ($user->status !== 'active') {
             throw ValidationException::withMessages([
                 'email' => ['This account is not active.']
             ]);
        }

        // Log the user in
        Auth::login($user, $credentials['remember'] ?? false);

        // Regenerate session for security
        request()->session()->regenerate();

        return new AuthResponseDTO(
            user: UserDTO::fromModel($user),
            message: 'Login successful'
        );
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    public function getAuthenticatedUser(): ?UserDTO
    {
        if (Auth::check()) {
            return UserDTO::fromModel(Auth::user());
        }
        return null;
    }
}
