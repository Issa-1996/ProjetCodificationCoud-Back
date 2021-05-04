<?php

namespace App\DataPersister;

use App\Entity\Etudiant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class EtudiantDataPersister implements DataPersisterInterface
{


    public function __construct(EntityManagerInterface $em, RequestStack $request, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->request = $request;
        $this->serializer = $serializer;
    }

    public function supports($data): bool
    {
        return $data instanceof Etudiant;
    }

    /**
     * @param Etudiant $data
     */
    public function persist($data)
    {
        $tab = $this->serializer->decode($this->request->getCurrentRequest()->getContent(), "json");
        $data = $this->serializer->denormalize($tab[0],"\App\Entity\Etudiant");
        $data->setMoyenneSession($tab[0]['moyenne']);
        $data->setUsername($tab[0]['numero']);
        $data->setPassword($tab[1]['password']);
        $data->setEmail($tab[1]['email']);
        $data->setRoles("ROLE_ETUDIANT");
        dd($data);
    }

    public function remove($data)
    {
        return;
    }
}