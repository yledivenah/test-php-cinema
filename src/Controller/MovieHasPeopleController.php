<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MovieHasPeople;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovieHasPeopleRepository;
use App\CustomService\AuthentificationService;

class MovieHasPeopleController extends AbstractController
{
    
    /**
     * @Route("api/moviehaspeoples", name="movieHasPeoples_store",methods={"POST"})
     */
    public function store(Request $request,
                          MovieHasPeopleRepository $movieHasPeopleRepository,
                          EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        } 
                            
        $data = json_decode($request->getContent());

        if (empty($data)) return $this->json(['status'=> 400,'message'=> 'The content of the request empty']);  
       
        if (empty($data->movie_id)) return $this->json(['status'=> 400,
                                                            'message'=> 'movie id required'
                                                        ],400);  
        
        if (empty($data->people_id)) return $this->json(['status'=> 400,
                                                            'message'=> 'people id required'],400);

        $movieHasPeople = $movieHasPeopleRepository->findOneBy(['movie_id'=>$data->movie_id,
                                                                'people_id'=>$data->people_id
                                                                    ]);

        if (!empty($movieHasPeople)) return $this->json(['status'=> 400,'message'=> 'MovioeHasPeople already exists'],400);
        
        /**
         * @todo vérifier que le people existe
         * @todo vérifier que le moovie existe
         */
                                                     
        if (empty($data->role)) return $this->json(['status'=> 400,
                                                        'message'=> 'role is required'],400);
                                                       
    
        if (empty($data->significance)) return $this->json(['status'=> 400,'message'=> 'significance is required'],400);
              
        if (!in_array($data->significance,MovieHasPeople::ARRAY_SIGNIFICANCE)) {
           return $this->json(['status'=> 400,'message'=> 'significance must have the following value: principal or secondaire'],400);
        }
        
        $movieHasPeople = new MovieHasPeople();
        $movieHasPeople->setMovieId($data->movie_id);
        $movieHasPeople->setPeopleId($data->people_id);
        $movieHasPeople->setRole($data->role);
        $movieHasPeople->setSignificance($data->significance);
          
        $em->persist($movieHasPeople);
        $em->flush();

        return $this->json(['status'=>200,'message'=>'MovioeHasPeople successfully added', 'data'=>$movieHasPeople],200);
        

    }


    /**
     * @Route("api/moviehaspeople/{movie_id}/{people_id}", name="movieHasPeoples_update",methods={"PUT"})
     */
    public function update(Request $request,
                          MovieHasPeopleRepository $movieHasPeopleRepository,  
                          EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }                     

        $data = json_decode($request->getContent());

        if (empty($data)) return $this->json(['status'=> 400,'message'=> 'The content of the request empty']);  
       
                      
        if (empty($data->role)) return $this->json(['status'=> 400,
                                                        'message'=> 'role is required'],400);
                                                    
        if (empty($data->significance)) return $this->json(['status'=> 400,'message'=> 'significance is required'],400);
              
        if (!in_array($data->significance,MovieHasPeople::ARRAY_SIGNIFICANCE)) {
           return $this->json(['status'=> 400,'message'=> 'significance must have the following value: principal or secondaire'],400);
        }
        
        $movieHasPeople = $movieHasPeopleRepository->findOneBy(['movie_id'=>$request->get('movie_id'),
                                                                'people_id'=>$request->get('people_id')
                                                               ]);
       
        if (empty($movieHasPeople)) return $this->json(['status'=> 400,'message'=> 'movieHasPeople not found'],400);

        $movieHasPeople->setRole($data->role);
        $movieHasPeople->setSignificance($data->significance);
          
        $em->persist($movieHasPeople);
        $em->flush();

        return $this->json(['status'=>200,'message'=>'MovioeHasPeople successfully updated', 'data'=>$movieHasPeople],200);


    }


    /**
     * @Route("api/moviehaspeople/{movie_id}/{people_id}", name="movieHasPeople",methods={"GET"})
     */
    public function show(Request $request,
                         MovieHasPeopleRepository $movieHasPeopleRepository) {

        $movieHasPeople = $movieHasPeopleRepository->findOneBy(['movie_id'=>$request->get('movie_id'),
                                                                'people_id'=>$request->get('people_id')
                                                               ]);
        if (empty($movieHasPeople)) {
            return $this->json(['status'=>400,'message'=>'MovieHasPeople not found'],400); 
        }

        return $this->json($movieHasPeople,200);
    }


    /**
     * @Route("api/moviehaspeople/{movie_id}/{people_id}", name="movieHasPeople_delete",methods={"DELETE"})
     */
    public function delete(Request $request,
                         MovieHasPeopleRepository $movieHasPeopleRepository,
                         EntityManagerInterface $em) {
                             
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }  

        $movieHasPeople = $movieHasPeopleRepository->findOneBy(['movie_id'=>$request->get('movie_id'),
                                                                'people_id'=>$request->get('people_id')]);
        if (empty($movieHasPeople)) {
            return $this->json(['status'=>400,'message'=>'MovieHasPeople not found'],400); 
        }

        $em->remove($movieHasPeople);
        $em->flush();
        return $this->json(['status'=>200,'message'=>'MovieHasPeople deleted successfully'],200);

    }
}
