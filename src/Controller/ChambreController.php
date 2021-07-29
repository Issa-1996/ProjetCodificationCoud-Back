<?php

namespace App\Controller;

use App\Entity\Lit;
use App\Entity\Campus;
use App\Entity\Chambre;
use App\Entity\Pavillon;
use App\Entity\QuotaLit;
use App\Repository\LitRepository;
use App\Repository\CampusRepository;
use App\Repository\NiveauRepository;
use App\Repository\ChambreRepository;
use App\Repository\PavillonRepository;
use App\Repository\QuotaLitRepository;
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
    private $niveauRepo;
    private $campusRepo;
    private $pavillonRepo;
    private $quotaRepo;


    public function  __construct(ChambreRepository $chambreRepo , 
                                CampusRepository $campusRepo , 
                                LitRepository $litRepo, 
                                NiveauRepository $niveauRepo,
                                PavillonRepository $pavillonRepo , 
                                QuotaLitRepository $quotaRepo , 
                                EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->chambreRepo = $chambreRepo;
        $this->litRepo = $litRepo;
        $this->niveauRepo = $niveauRepo;
        $this->campusRepo = $campusRepo;
        $this->pavillonRepo = $pavillonRepo;
        $this->quotaRepo = $quotaRepo;
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
        for ($i = 1; $excel_file[$i][0]!=nniveauRepoull; $i++ ){
            $camp=$this->campusRepo->findOneByNom($excel_file[$i][4]);
            //dd($excel_file[$i][3]);
            if ($camp!=null){
                $pavillon=$camp->getPavillons();
                //dd(explode('_',$excel_file[$i][3])[1]);
                foreach($pavillon as $keyPav){
                    if($keyPav->getNom()==$excel_file[$i][1]){
                        // foreach($keyPav->getChambres() as $chamb){
                        //     if($chamb->getNumero()==$excel_file[$i][2]){
                                //foreach($chamb->getLits() as $keyLi){
                                    //if($keyLi->getNumero()==$excel_file[$i][3]){
                                       // echo $i;
                                        // $lits= new Lit();
                                        // $lits->setNumero($excel_file[$i][3]);
                                        // $chamb->addLit($lits);
                                        // $this->manager->persist($camp);
                                        // $this->manager->flush();
                                    //}
                                //}
                            //}else{
                                //echo $i;
                                $lits= new Lit();
                                $lits->setNumero($excel_file[$i][2].'('.$camp->getNom().')_'.explode('_',$excel_file[$i][3])[1]);
                                $chambres=new Chambre();
                                $chambres->setNumero($excel_file[$i][2]);
                                $chambres->addLit($lits);
                                $keyPav->addChambre($chambres);
                                $this->manager->persist($camp);
                                $this->manager->flush();
                            //}
                        //}
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
    /**
     * @Route(
     *      path="/api/admin/importQuota",
     *      name="import_quota",
     *      methods="POST",
     * )
     * @param Request $request
     * @throws Exception
     */
    //importation fichier Excel
    public function importQuota(Request $request){
        $errors = [];
        $doc = $request ->files->get('excelFile');
        $file= IOFactory::identify($doc);
        $reader= IOFactory::createReader($file);
        $spreadsheet=$reader->load($doc);
        $excel_file= $spreadsheet->getActivesheet()->toArray();
        for ($i = 1; $excel_file[$i][0]!=null; $i++ ){

            $lits=$this->litRepo->findOneByNumero($excel_file[$i][2]);
            $niveau=$this->niveauRepo->findOneByNom($excel_file[$i][1]);
            if(!$lits){
                array_push($errors, "Le numero du  lits: ".$excel_file[$i][2]." n'existe pas.");
            }
            if(!$niveau){
                array_push($errors, "Le niveau: ".$excel_file[$i][1]." n\'existe pas.");
            }
            if($lits && $niveau){
                $quota=$this->quotaRepo->findOneByNiveauQuot($excel_file[$i][2], $excel_file[$i][1]);
                if(!$quota){
                    $qot=new QuotaLit();
                    $qot->setNiveau($niveau);
                    $qot->addLit($lits);
                    $this->manager->persist($qot);
                    $this->manager->flush();
                }
            }
        }
        return new JsonResponse($errors, Response::HTTP_OK);
    }
}