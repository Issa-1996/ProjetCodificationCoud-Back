<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Repository\LitRepository;
use App\Repository\EtudiantRepository;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class EtudiantController extends AbstractController
{

    private $etuRepo;
    private $litRep;


    public function  __construct(EtudiantRepository $etuRepo , LitRepository $litRep)

    {
        $this->etuRepo = $etuRepo;
        $this->litRep = $litRep;

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
      	   // dd($excel_file);
      	   for ($i = 1; $i < count($excel_file); $i++ ){

               if (($etudiant = $this->etuRepo->findOneByNumero($excel_file[$i][0])) && ($lit = $this->litRep->findOneByNumero($excel_file[$i][4]))){
                   $reservation = $etudiant->getReservation();
                   foreach ($reservation as $rest){
                       if ($rest->getAnnee()== '2021'){
                           $affection = new Affectation();
                           $affection->setReservation($rest);
                           $affection->setLit($lit);
                           dd($affection);
                       }
                   }

               }

           }
       return new JsonResponse('l\'opération est traitée avec succes', Response::HTTP_OK);

          }

}
