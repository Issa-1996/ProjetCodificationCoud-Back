<?php

namespace App\Controller;

use App\Entity\Lit;
use App\Entity\Campus;
use App\Entity\Chambre;
use App\Entity\Pavillon;
use App\Repository\LitRepository;
use App\Repository\CampusRepository;
use App\Repository\ChambreRepository;
use App\Repository\PavillonRepository;
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
    private $litRepo;
    private $campusRepo;
    private $pavillonRepo;


    public function  __construct(ChambreRepository $chambreRepo , 
                                CampusRepository $campusRepo , 
                                LitRepository $litRepo,
                                PavillonRepository $pavillonRepo , 
                                EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->chambreRepo = $chambreRepo;
        $this->litRepo = $litRepo;
        $this->campusRepo = $campusRepo;
        $this->pavillonRepo = $pavillonRepo;

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
              //dd(count($excel_file));
            for ($i = 1; $i < count($excel_file); $i++ ){
                dd($lit = $this->litRepo->findOneByNumero($excel_file[$i][3]));
                //dd($pavillon = $this->pavillonRepo->findOneByNom($excel_file[$i][1]));
                //dd($campus = $this->campusRepo->findOneByNom($excel_file[$i][4]));
                if (($chambre = $this->chambreRepo->findOneById($excel_file[$i][0]))){

                    $lits= new Lit();
                    $lits->setNumero($excel_file[$i][3]);

                    $chambres=new Chambre();
                    $chambres->setNumero($excel_file[$i][2]);
                    $chambres->addLit($lits);

                    $pavillon = new Pavillon();
                    $pavillon->setNom($excel_file[$i][1]);
                    $pavillon->addChambre($chambres);

                    $campus = new Campus();
                    $campus->setNom($excel_file[$i][4]);
                    $campus->addPavillon($pavillon);

                    //dd($campus);
                    //$chambre->setChambre($excel_file[$i][2]);
                    //$chambre->setLit($excel_file[$i][3]);
                    //$chambre->setCampus($$excel_file[$i][4]);
                    //$lit->setQuota($excel_file[$i][2]);
                    //dd($chambre);
                    $this->manager->persist($campus);
                    $this->manager->flush();
                    return new JsonResponse($campus, Response::HTTP_OK);
                }
            }
    }
}
