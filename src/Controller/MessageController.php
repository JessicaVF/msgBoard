<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message", methods={"GET"})
     */
    public function index(MessageRepository $repository): Response
    {
        $messages = $repository->findAll();
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }
    /**
     * @Route("/messageApi", name="messageApi")
     */
    public function indexApi(MessageRepository $repository): Response
    {
        $messages = $repository->findAll();
        return $this->json($messages);
    }
    /**
     * @Route("/message/create", name ="createMessage", methods= {"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {

        $myRequest = $request->getContent();

        $message = $serializer->deserialize($myRequest, Message::class, 'json');

        $manager->persist($message);

        $manager->flush();

        return  $this->json($message);
    }
    /**
     * @Route("/message/delete/{id}", name="deleteMessage", methods = {"DELETE"})
     */
    public function delete(Message $message, EntityManagerInterface $manager): Response
    {
        $manager->remove($message);
        $manager->flush();
        $data = "message deleted";
        return $this->json($data, 200, []);
    }
    /**
     * @Route("/message/edit/{id}", name="editMessage", methods={"PATCH"})
     */
    public function edit(Message $message, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager):Response
    {

        $myRequest = $request->getContent();
        $messageEdit = $serializer->deserialize($myRequest, Message::class, 'json');
        $message->setTitle($messageEdit->getTitle());
        $message->setContent($messageEdit->getContent());
        $manager->persist($message);
        $manager->flush();
        return  $this->json($message);
    }
}
