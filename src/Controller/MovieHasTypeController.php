<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MovieHasType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovieHasTypeRepository;
use App\CustomService\AuthentificationService;

class MovieHasTypeController extends AbstractController
{
    /**
     * @Route("api/moviehastypes", name="movieHasType_store",methods={"POST"})
     */
    public function store(Request $request,
                          MovieHasTypeRepository $movieHasTypeRepository,
                          EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }                     
                            
        $data = json_decode($request->getContent());

        if (empty($data)) return $this->json(['status'=> 400,'message'=> 'The content of the request empty']);  
       
        if (empty($data->movie_id)) return $this->json(['status'=> 400,
                                                            'message'=> 'movie id required'
                                                        ],400);

        if (empty($data->type_id)) return $this->json(['status'=> 400,
                                                            'message'=> 'type id required'],400);
        
        $movieHasType = $movieHasTypeRepository->findOneBy(['movie_id'=>$data->movie_id,
                                                            'type_id'=>$data->type_id
                                                            ]); 

        if (!empty($movieHasType)) return $this->json(['status'=> 400,'message'=> 'MovieHasType already exists'],400);

        /**
         * @todo vérifier que le moovie existe
         * @todo vérifier que le type  existe
         */
                                                  
        $movieHasType = new MovieHasType();
        $movieHasType->setMovieId($data->movie_id);
        $movieHasType->setTypeId($data->type_id);
       
        $em->persist($movieHasType);
        $em->flush();

        return $this->json(['status'=>200,'message'=>'MovieHasType successfully added', 'data'=>$movieHasType],200);
        

    }

    /**
     * @Route("api/moviehastype/{movie_id}/{type_id}", name="movieHasType",methods={"GET"})
     */
    public function show(Request $request,
                         MovieHasTypeRepository $movieHasTypeRepository) {

        $movieHasType = $movieHasTypeRepository->findOneBy(['movie_id'=>$request->get('movie_id'),
                                                            'type_id'=>$request->get('type_id')
                                                               ]);
        if (empty($movieHasType)) {
            return $this->json(['status'=>400,'message'=>'MovieHasType not found'],400); 
        }

        return $this->json($movieHasType,200);
    }


    /**
     * @Route("api/moviehastype/{movie_id}/{type_id}", name="movieHasType_delete",methods={"DELETE"})
     */
    public function delete(Request $request,
                          MovieHasTypeRepository $movieHasTypeRepository,
                          EntityManagerInterface $em) {
    
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }
        
         $movieHasType = $movieHasTypeRepository->findOneBy(['movie_id'=>$request->get('movie_id'),
                                                             'type_id'=>$request->get('type_id')
                                                             ]);

        if (empty($movieHasType)) {
            return $this->json(['status'=>400,'message'=>'MovieHasType not found'],400); 
        }

        $em->remove($movieHasType);
        $em->flush();
        return $this->json(['status'=>200,'message'=>'MovieHasType deleted successfully'],200);

    }


    
}
