<?php

namespace App\Controller;

use App\Entity\Affectation;
use DateTime;
use App\Repository\LitRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EtudiantController extends AbstractController
{

    private $etuRepo;
    private $litRep;
    private $manager;


    public function  __construct(EtudiantRepository $etuRepo,
                                 EntityManagerInterface $manager,
                                 LitRepository $litRep )

    {
        $this->etuRepo = $etuRepo;
        $this->litRep = $litRep;
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
        $errors = [];
        $annee = new DateTime;
        $this->annee = $annee->format('Y');

          $doc =$request ->files->get('excelFile');
           		$file= IOFactory::identify($doc);
           		$reader= IOFactory::createReader($file);
           		$spreadsheet=$reader->load($doc);
           		$excel_file= $spreadsheet->getActivesheet()->toArray();
      	   // dd($excel_file);
      	   for ($i = 1; $i < count($excel_file); $i++ ) {
               $etudiant = $this->etuRepo->findOneByNumero($excel_file[$i][0]);
               $lit = $this->litRep->findOneByNumero($excel_file[$i][4]);

               if(!$etudiant){
                //  return new Response('Etudiant(e) non reconnu(e).', Response::HTTP_FORBIDDEN);
                  $error = "Ce numero carte n\' existe pas";
                array_push($errors, $error);
               }
              if(!$lit){
                 // return new Response('la colonne lit est  vide.', Response::HTTP_FORBIDDEN);
              $error = "Ce lit est déja attribué";
               array_push($errors, $error);
               }
               if ($etudiant && $lit) {
                  // dd($etudiant);
                   $reservation = $etudiant->getReservation();
                   foreach ($reservation as $rest) {
                       if ($rest->getAnnee() == $annee) {
                           $affection = new Affectation();
                           $affection->setReservation($rest);
                           $affection->setLit($lit);
                        //  dd($affection);
                           $this->manager->persist($affection);
                           $this->manager->flush();

                       }
                   }
               }elseif (!$etudiant && !$lit) {
                   $error = "message d erreur";
                   array_push($errors, $error);
               }
             //  return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);

           }
      return new JsonResponse('l\'opération est bien traitée avec succes', Response::HTTP_OK);

          }


}
