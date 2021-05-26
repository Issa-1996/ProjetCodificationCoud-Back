<?php

namespace App\DataPersister;

use App\Entity\Niveau;
use App\Entity\Faculte;
use App\Entity\Etudiant;
use App\Entity\Departement;
use App\Repository\UserRepository;
use App\Repository\NiveauRepository;
use App\Repository\FaculteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use App\Repository\EtudiantApiRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class EtudiantDataPersister implements DataPersisterInterface
{
    private $request;
    private $serializer;
    private $niveau_repo;
    private $user_repo;
    private $dep_repo;
    private $fac_repo;
    private $em;

    public function __construct(EntityManagerInterface $em,
                                RequestStack $request,
                                SerializerInterface $serializer,
                                NiveauRepository $niveau_repo,
                                FaculteRepository $fac_repo,
                                DepartementRepository $dep_repo,
                                UserRepository $user_repo,
                                EtudiantApiRepository $etu_api_repo)
    {
        $this->em = $em;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->fac_repo = $fac_repo;
        $this->dep_repo = $dep_repo;
        $this->niveau_repo = $niveau_repo;
        $this->user_repo = $user_repo;
        $this->etu_api_repo = $etu_api_repo;
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
        if (!$etu = $this->etu_api_repo->findOneByNumero($tab['numEtudiant'])) {
            return new Response('Etudiant(e) non reconnu(e).', Response::HTTP_FORBIDDEN);
        }

        if ($this->user_repo->findOneByUsername($tab['numEtudiant'])) {
            return new Response('Déja inscrit, connectez-vous !!!', Response::HTTP_FORBIDDEN);
        }

        $dateNaissance = implode('/',array_reverse(explode('-',$tab['dateNaissance'])));

        // Vérifier la compatibilité des dates de naissance et numéro d'indentité national. A décommenter
        // if ($tab['numIdentite'] != $etu->getNumIdentite() || $dateNaissance != $etu->getDateNaissance()) {
        //     return new Response('Les infos ne correspondent pas.', Response::HTTP_FORBIDDEN);
        // }

        $data = $this->serializer->denormalize($tab,"\App\Entity\Etudiant");
        $data->setUsername($tab['numEtudiant']);
        $data->setRoles(["ROLE_ETUDIANT"]);
        $data->setDateNaissance($dateNaissance);

        if (!$niveau = $this->niveau_repo->findOneByNom($etu->getNiveauFormation())) {
            $niveau = new Niveau();
        }
        $niveau->setNom($etu->getNiveauFormation());
        $data->setNiveau($niveau);

        if (!$departement = $this->dep_repo->findOneByNom($etu->getDepartement())) {
            $departement = new Departement();
        }
        $departement->setNom($etu->getDepartement());
        $niveau->setDepartement($departement);

        if (!$faculte = $this->fac_repo->findOneByNom($etu->getEtablissement())) {
            $faculte = new Faculte();
        }
        $faculte->setNom($etu->getEtablissement());
        $departement->setFaculte($faculte);

        $data->setMoyenneSession(strval($etu->getMoyenne()));
        $data->setPrenoms($etu->getPrenoms());
        $data->setNom($etu->getNom());
        $data->setLieuNaissance($etu->getLieuNaissance());

        $this->em->persist($data);
        $this->em->flush();
        return $data;
    }

    public function remove($data)
    {
        return;
    }
}
