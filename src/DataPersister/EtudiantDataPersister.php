<?php

namespace App\DataPersister;

use DateTime;
use App\Entity\Niveau;
use App\Entity\Faculte;
use App\Entity\Etudiant;
use App\Entity\Departement;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Repository\NiveauRepository;
use App\Repository\FaculteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class EtudiantDataPersister implements DataPersisterInterface
{
    public function __construct(EntityManagerInterface $em, RequestStack $request, SerializerInterface $serializer, NiveauRepository $niveau_repo, FaculteRepository $fac_repo, DepartementRepository $dep_repo, UserRepository $user_repo)
    {
        $this->em = $em;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->fac_repo = $fac_repo;
        $this->dep_repo = $dep_repo;
        $this->niveau_repo = $niveau_repo;
        $this->user_repo = $user_repo;
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
        if ($this->request->getCurrentRequest()->getMethod() === 'PUT' && preg_match( '/reserver/' , $this->request->getCurrentRequest()->getPathInfo())) {
            $tab = $this->serializer->decode($this->request->getCurrentRequest()->getContent(), "json");
            if ($etu = $this->user_repo->findOneByUsername($tab['username'])) {
                if (count($etu->getReservation()) === 0){
                    $reservation = new Reservation();
                    $reservation->setAnnee(new DateTime());
                    $etu->addReservation($reservation);
                    $this->em->persist($etu);
                    $this->em->flush();
                    
                };
                return $etu;
            }
        }
        else {
            $tab = $this->serializer->decode($this->request->getCurrentRequest()->getContent(), "json");
            if ($this->user_repo->findOneByUsername($tab[0]['numero'])) {
                return new Response('Erreur ! vous vous êtes déjà inscrit', Response::HTTP_FORBIDDEN);
            }
            $data = $this->serializer->denormalize($tab[0],"\App\Entity\Etudiant");
            $data->setMoyenneSession($tab[0]['moyenne']);
            $data->setUsername($tab[0]['numero']);
            $data->setPassword($tab[1]['password']);
            $data->setEmail($tab[1]['email']);
            $data->setRoles(["ROLE_ETUDIANT"]);

            if (!$niveau = $this->niveau_repo->findOneByNom($tab[0]['niveauFormation'])) {
                $niveau = new Niveau();
            }
            $niveau->setNom($tab[0]['niveauFormation']);
            $data->setNiveau($niveau);

            if (!$departement = $this->dep_repo->findOneByNom($tab[0]['departement'])) {
                $departement = new Departement();
            }
            $departement->setNom($tab[0]['departement']);
            $niveau->setDepartement($departement);

            if (!$faculte = $this->fac_repo->findOneByNom($tab[0]['etablissement'])) {
                $faculte = new Faculte();
            }
            $faculte->setNom($tab[0]['etablissement']);
            $departement->setFaculte($faculte);

            $this->em->persist($data);
            $this->em->flush();
            return $data;
        }
    }

    public function remove($data)
    {
        return;
    }
}
