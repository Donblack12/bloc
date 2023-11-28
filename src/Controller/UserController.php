<?php
// src/Controller/UserController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    #[Route('/user/liste', name: 'user_list')]
    public function userList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
