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
        $errors=[];
        $file= IOFactory::identify($doc);
        $reader= IOFactory::createReader($file);
        $spreadsheet=$reader->load($doc);
        $excel_file= $spreadsheet->getActivesheet()->toArray();
        if((strtolower($excel_file[0][0])!="numero") || (strtolower($excel_file[0][1])!="pavillon") || (strtolower($excel_file[0][2])!="chambre") || (strtolower($excel_file[0][3])!="lit") || (strtolower($excel_file[0][4])!="campus")){
            array_push($errors, "Le fichier n'est pas reconnu!");
            return new JsonResponse($errors, Response::HTTP_OK);
        }
        for ($i = 1; $excel_file[$i][0]!=null; $i++ ){
            $camp=$this->campusRepo->findOneByNom($excel_file[$i][4]);
            if ($camp!=null){
                $pavillon=$this->pavillonRepo->findOneByCampus($excel_file[$i][4], $excel_file[$i][1]);
                if($pavillon!=null){
                    $chambre=$this->chambreRepo->findOneByPavillon($excel_file[$i][1], $excel_file[$i][2]);
                    if($chambre!=null){
                        $numLit=$excel_file[$i][2].'('.$camp->getNom().')_'.explode('_',$excel_file[$i][3])[1];
                        $lit=$this->litRepo->findOneByChambre($excel_file[$i][2], $numLit);
                        if(!$lit){
                            $lits= new Lit();
                            $lits->setNumero($numLit);
                            $chambre->addLit($lits);
                            $this->manager->persist($lits);
                            $this->manager->flush();
                        }
                    }else{
                        $lits= new Lit();
                        $lits->setNumero($excel_file[$i][2].'('.$camp->getNom().')_'.explode('_',$excel_file[$i][3])[1]);
                        $chambres=new Chambre();
                        $chambres->setNumero($excel_file[$i][2]);
                        $chambres->addLit($lits);
                        $pavillon->addChambre($chambres);
                        $this->manager->persist($chambres);
                        $this->manager->flush();
                    }
                }else{
                    $lits= new Lit();
                    $lits->setNumero($excel_file[$i][2].'('.$camp->getNom().')_'.explode('_',$excel_file[$i][3])[1]);
                    $chambres=new Chambre();
                    $chambres->setNumero($excel_file[$i][2]);
                    $chambres->addLit($lits);
                    $pavillon=new Pavillon();
                    $pavillon=setNom($excel_file[$i][1]);
                    $pavillon->addChambre($chambres);
                    $this->manager->persist($pavillon);
                    $this->manager->flush();
                }
            }else{
                $lits= new Lit();
                $lits->setNumero($excel_file[$i][2].'('.$camp->getNom().')_'.explode('_',$excel_file[$i][3])[1]);
                $chambres=new Chambre();
                $chambres->setNumero($excel_file[$i][2]);
                $chambres->addLit($lits);
                $pavillon=new Pavillon();
                $pavillon=setNom($excel_file[$i][1]);
                $pavillon->addChambre($chambres);
                $campus=new Campus();
                $campus=setNom($excel_file[$i][4]);
                $this->manager->persist($pavillon);
                $this->manager->flush();
            }
        }
        return new JsonResponse("L'importation est terminée", Response::HTTP_OK);
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
        if((strtolower($excel_file[0][0])!="id_quota") || (strtolower($excel_file[0][1])!="niveauformation") || (strtolower($excel_file[0][2])!="lit")){
            array_push($errors, "Le fichier n'est pas reconnu!");
            return new JsonResponse($errors, Response::HTTP_OK);
        }
        for ($i = 1; $excel_file[$i][0]!=null; $i++ ){
            $quota=$this->quotaRepo->findOneByNiveauQuot($excel_file[$i][2], $excel_file[$i][1]);
            $niveau=$this->niveauRepo->findOneByNom($excel_file[$i][1]);
            $lits=$this->litRepo->findOneByNumero($excel_file[$i][2]);
            if(!$niveau){
                array_push($errors, "Le niveau ".$excel_file[$i][1]." n'existe pas ");
            }
            if(!$lits){
                array_push($errors, "Le lit ".$excel_file[$i][2]." n'existe pas");
            }
            if(!$quota && $lits && $niveau){
                $qot=new QuotaLit();
                $qot->setNiveau($niveau);
                $qot->addLit($lits);
                $this->manager->persist($qot);
                $this->manager->flush();
            }
        }
        return new JsonResponse($errors, Response::HTTP_OK);
    }
}