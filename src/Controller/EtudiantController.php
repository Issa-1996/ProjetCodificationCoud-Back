<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class EtudiantController extends AbstractController
{

    /**
     * @Route(
     *      path="/api/admin/importList",
     *      name="import_list",
     *      methods="POST",
     * )
     */

    // importation fichier Excel
    public function import(Request $request){
        $doc = $request ->files->get('excelFile');
           		$file= IOFactory::identify($doc);
           		$reader= IOFactory::createReader($file);
           		$spreadsheet=$reader->load($doc);
           		$excel_file= $spreadsheet->getActivesheet()->toArray();
      	   // dd($excel_file);
        return new JsonResponse($excel_file, Response::HTTP_OK);

          }

}
