<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        // Validasi gambar jika ada
        if (isset($data['avatar'])) {
            $data['avatar'] = $this->handleProfilePicture($data['avatar']);
        }

        return $this->userRepository->store($data);
    }

    public function login(array $data)
    {
        if (!Auth::attempt($data)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        }

        $user = Auth::user();

        // Generate JWT token menggunakan JWTAuth
        $token = JWTAuth::fromUser($user);

        return ['user' => $user, 'token' => $token];
    }

    public function updateProfile($user, array $data)
    {
        // Validasi gambar jika ada
        if (isset($data['picture'])) {
            $data['picture'] = $this->handleProfilePicture($data['picture']);
        }

        return $this->userRepository->update($user, $data);
    }

    /**
     * Menangani unggahan gambar profil dengan validasi dan penyimpanan yang aman
     */
    private function handleProfilePicture($file)
    {
        // Validasi file gambar menggunakan Laravel Validator
        $validator = Validator::make(['picture' => $file], [
            'picture' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Membuat nama file yang unik
        $filename = time() . '_' . $file->getClientOriginalName();

        // Menyimpan file di folder 'profile_pictures' pada disk 'public'
        $path = $file->storeAs('profile_pictures', $filename, 'public');

        return $path;
    }
}
