<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(Security $security): void
    {
        $security->logout();
        $security->logout(false);
    }
}
