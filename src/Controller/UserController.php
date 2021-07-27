<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/user/register", name="register", methods={"POST"})
     */
        public function  register(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher):Response
        {

            $myRequest = $request->getContent();

            $user = $serializer->deserialize($myRequest, User::class, 'json');

            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hashedPassword);

            $manager->persist($user);

            $manager->flush();

            return  $this->json($user);


        }
    /**
     * @Route("/user/login", name="login")
     */
    public function login():Response
    {
        return $this->render('user/login.html.twig');
    }
    /**
     * @Route("/user/logout", name="logout")
     */
    public function logout():Response
    {

    }
}
