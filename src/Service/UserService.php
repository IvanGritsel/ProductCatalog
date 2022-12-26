<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function getUsersWithoutPrivileges(): array
    {
        $users = $this->userRepository->findAll();
        $usersNoPrivileges = [];
        foreach ($users as $user) {
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                $user->setPassword('');
                $usersNoPrivileges[] = $user;
            }
        }
        return $usersNoPrivileges;
    }

    public function registerUser(array $credentials): bool|User
    {
        $user = new User();
        $user->setEmail($credentials['username']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $credentials['password']);
        $user->setPassword($hashedPassword);
        try {
            $this->userRepository->save($user, true);

            return $user;
        } catch (Exception $e) {
            return false;
        }
    }

    public function grantPrivileges(int $id): bool
    {
        try {
            $user = $this->userRepository->find($id);
            $user->setRoles(['ROLE_ADMIN']);
            $this->userRepository->save($user, true);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function refusePrivileges(int $id): bool
    {
        try {
            $user = $this->userRepository->find($id);
            $this->userRepository->remove($user, true);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}