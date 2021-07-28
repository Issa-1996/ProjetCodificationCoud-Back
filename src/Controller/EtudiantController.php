<?php

namespace App\Controller;

use DateTime;
use App\Entity\Affectation;
use App\Repository\LitRepository;
use App\Repository\NiveauRepository;
use App\Repository\EtudiantRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffectationRepository;
use App\Repository\ReservationRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EtudiantController extends AbstractController
{

    private $etuRepo;
    private $litRep;
    private $manager;


    public function  __construct(EtudiantRepository $etuRepo,
                                 EntityManagerInterface $manager,
                                 LitRepository $litRep,
                                 ReservationRepository $reserRepo,
                                 NiveauRepository $niveauRepo,
                                 AffectationRepository $affectationRepo )

    {
        $this->etuRepo = $etuRepo;
        $this->litRep = $litRep;
        $this->manager = $manager;
        $this->reservationRepo = $reserRepo;
        $this->niveauRepository = $niveauRepo;
        $this->affectationRepo = $affectationRepo;
    }
    /**
     * @Route(
     *      path="/api/admin/importList",
     *      name="import_list",
     *      methods="POST",
     * )
     * @param Request $request
     * @throws Exception
     */

    // importation fichier Excel de codification
    public function import(Request $request){
        $errors = [];
        $annee = new DateTime;
        $this->annee = $annee->format('Y');

        $doc =$request ->files->get('excelFile');
        $file= IOFactory::identify($doc);
        $reader= IOFactory::createReader($file);
        $spreadsheet=$reader->load($doc);
        $excel_file= $spreadsheet->getActivesheet()->toArray();
        $niveau  = explode( '__', $doc->getClientOriginalName())[0];
        if(!$this->niveauRepository->findOneByNom($niveau)){
            array_push($errors, "Le fichier choisi n'est pas reconnu par le système.");
            return new JsonResponse($errors, Response::HTTP_OK);
        };
        for ($i = 1; $excel_file[$i][0]!=null; $i++ ) {
            $etudiant = $this->etuRepo->findOneByNumero($excel_file[$i][0]);
            $lit = $this->litRep->findOneByNumero($excel_file[$i][4]);

            if(!$etudiant){
                array_push($errors, "Le numero Etudiant: ".$excel_file[$i][0]." n'existe pas.");
            }
            if(!$lit){
                array_push($errors, "Le numero de Lit: ".$excel_file[$i][4]." n'existe pas.");
            }
            if ($etudiant && $lit) {
                $reservation = $this->reservationRepo->findOneByStudentId($etudiant->getId());

                if ($reservation) {
                    $affectation = $this->affectationRepo->findOneByResId($reservation->getId());
                    if ($affectation==null) {
                        $affectation = new Affectation();
                        $affectation->setReservation($reservation);
                    }
                    $affectation->setLit($lit);
                    $this->manager->persist($affectation);
                    $this->manager->flush();
                }
            }
        }
        return new JsonResponse($errors, Response::HTTP_OK);
    }
}
