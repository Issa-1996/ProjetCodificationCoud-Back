<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Entity\Etudiant;
use App\Entity\Reservation;
use App\Repository\LitRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Serializer\SerializerInterface;


class EtudiantController extends AbstractController
{
    private $manager;
    private $etuRepo;
    private $litRep;
    private $serializer;


    public function  __construct(EtudiantRepository $etuRepo ,
                                 EntityManagerInterface $manager,
                                 LitRepository $litRep,
                                 SerializerInterface $serializer)

    {
        $this->etuRepo = $etuRepo;
        $this->litRep = $litRep;
        $this->serializer = $serializer;
        $this->manager = $manager;

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

    // importation fichier Excel
    public function import(Request $request){
        $doc = $request ->files->get('excelFile');
        $file= IOFactory::identify($doc);
        $reader= IOFactory::createReader($file);
        $spreadsheet=$reader->load($doc);
        $excel_file= $spreadsheet->getActivesheet()->toArray();

        //dd($excel_file);
        for ($i = 1; $i < count($excel_file); $i++ ){
            if (($etudiant = $this->etuRepo->findOneByNumero($excel_file[$i][0])) && ($lit = $this->litRep->findOneByNumero($excel_file[$i][4]))){
                $reservation = $etudiant->getReservation();
                    dd($reservation);
            }
        }
      // return new JsonResponse($excel_file, Response::HTTP_OK);
    }
}
