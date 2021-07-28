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
    //importation fichier Excel
    public function import(Request $request){
        $doc = $request ->files->get('excelFile');
        $file= IOFactory::identify($doc);
        $reader= IOFactory::createReader($file);
        $spreadsheet=$reader->load($doc);
        $excel_file= $spreadsheet->getActivesheet()->toArray();
        for ($i = 1; $excel_file[$i][0]!=null; $i++ ){
            echo $excel_file[$i][0];
            $camp=$this->campusRepo->findOneByNom($excel_file[$i][4]);
            if ($camp!=null){
                $pavillon=$camp->getPavillons();
                foreach($pavillon as $keyPav){
                    if($keyPav->getNom()==$excel_file[$i][1]){
                        foreach($keyPav->getChambres() as $chamb){
                            if($chamb->getNumero()==$excel_file[$i][2]){
                                foreach($chamb->getLits() as $keyLi){
                                    if(!$keyLi->getNumero()==$excel_file[$i][3]){
                                        $lits= new Lit();
                                        $lits->setNumero($excel_file[$i][3]);
                                        $chamb->addLit($lits);
                                        $this->manager->persist($camp);
                                        $this->manager->flush();
                                    }
                                }
                            }else{
                                $lits= new Lit();
                                $lits->setNumero($excel_file[$i][3]);
                                $chambres=new Chambre();
                                $chambres->setNumero($excel_file[$i][2]);
                                $chambres->addLit($lits);
                                $keyPav->addChambre($chambres);
                                $this->manager->persist($camp);
                                $this->manager->flush();
                            }
                        }
                    }else{
                        $lits= new Lit();
                        $lits->setNumero($excel_file[$i][3]);
                        $chambres=new Chambre();
                        $chambres->setNumero($excel_file[$i][2]);
                        $chambres->addLit($lits);
                        $pavillon = new Pavillon();
                        $pavillon->setNom($excel_file[$i][1]);
                        $pavillon->addChambre($chambres);
                        $camp->setNom($excel_file[$i][4]);
                        $camp->addPavillon($pavillon);
                        $this->manager->persist($camp);
                        $this->manager->flush();
                    }
                }
            }else{
                $lits= new Lit();
                $lits->setNumero($excel_file[$i][3]);
                $chambres=new Chambre();
                $chambres->setNumero($excel_file[$i][2]);
                $chambres->addLit($lits);
                $pavillon = new Pavillon();
                $pavillon->setNom($excel_file[$i][1]);
                $pavillon->addChambre($chambres);
                $campus=new Campus();
                $campus->setNom($excel_file[$i][4]);
                $campus->addPavillon($pavillon);
                $this->manager->persist($campus);
                $this->manager->flush();
            }
        }
        return new JsonResponse($camp, Response::HTTP_OK);
    }
}