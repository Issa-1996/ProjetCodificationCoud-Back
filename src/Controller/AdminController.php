<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function  __construct(EntityManagerInterface $manager,
                                 UserPasswordEncoderInterface $encoder,
                                 SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *      path="/api/admin/inscription", 
     *      name="user",
     *      methods="POST",
     * )
     */
    public function addUser(UserPasswordEncoderInterface $encoder,
                            Request $request,
                            SerializerInterface $serializer, \Swift_Mailer $mailer)
    {
        
        $tab = $this->serializer->decode($request->getContent(), "json");
        $user=new User();
        $user->setPrenoms($tab["prenoms"]);
        $user->setNom($tab["nom"]);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setUsername($tab["username"]);
        $user->setPassword('password');
        $email=$tab["username"];
        $password="password";                                                                                                                               
       // dd($email);
        $message = (new \Swift_Message('Coordonnées de connexion '))
                ->setFrom('mailcoud@gmail.com')
                ->setTo($email)

                ->setBody("Bienvenue ".$tab["prenom"]." ".$tab["nom"]." dans l'espace Administrateur de la gestion des codifications du COUD.\n Vos informations de connexion sont:\n \n email: ".$email."\n password: ".$password."\n \n Veuiller l'utiliser pour vous connecter à l'espace administrateur.");
                //dd($message);
            $mailer->send($message);
            //return $this->render('base.html.twig');
        //dd($user);
        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse($tab, Response::HTTP_OK);
    }
}
