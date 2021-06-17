<?php

namespace App\Controller;

use App\Repository\LitRepository;
use App\Repository\ChambreRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
    private $chambreRepo;
    private $litRep;


    public function  __construct(ChambreRepository $chambreRepo , 
                                LitRepository $litRep,
                                EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->chambreRepo = $chambreRepo;
        $this->litRep = $litRep;

    }
    /**
     * @Route(
     *      path="/api/admin/importListChambre",
     *      name="import_listChambre",
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
                //dd($excel_file[$i][2]);
                //dd($lit = $this->litRep->findOneByNumero($excel_file[$i][1]));
                //dd($chambre = $this->chambreRepo->findOneById($excel_file[$i][0]));
                if (($chambre = $this->chambreRepo->findOneById($excel_file[$i][0])) && ($lit = $this->litRep->findOneByNumero($excel_file[$i][1]))){
                    $lit->setNumero($excel_file[$i][1]);
                    $lit->setChambre($chambre);
                    //$lit->setQuota($excel_file[$i][2]);
                    dd($lit);
                    $this->manager->persist($lit);
                    $this->manager->flush();
                    return new JsonResponse($lit, Response::HTTP_OK);
                }
            }
    }
}
