<?php
namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/")
     */

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $users)

    {
        return $this->render('home/index.html.twig',[
            'users' => $users->findAll(),
        ]);
    }
}
