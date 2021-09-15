<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MovieRepository;
use App\Repository\PeopleRepository;
use App\Repository\MovieHasPeopleRepository;
use App\Repository\MovieHasTypeRepository;
use App\Repository\TypeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use App\CustomService\AuthentificationService;
use App\CustomService\RapidApiService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


class MovieController extends AbstractController
{
    /**
     * @Route("api/movies", name="moovies_index",methods={"GET"})
     */
    public function index(Request $request, MovieRepository $movieRepository) : JsonResponse
    {

        $page = $request->query->get('page') ?? 1;
        $movie_per_page = $this->getParameter('movie_per_page');
        $movies = $movieRepository->findAllByPage($page,$movie_per_page);
        return $this->json($movies,200);
        
    }

    /**
     * @Route("api/movies", name="movies_store",methods={"POST"})
     */
    public function store(Request $request,
                          EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }                 
        

        $data = json_decode($request->getContent());

        if (empty($data)) return $this->json(['status'=> 400,'message'=> 'The content of the request empty']);  
       
        if (empty($data->duration)) return $this->json(['status'=> 400,
                                                            'message'=> 'Duration is required'
                                                        ],400);  
        
        if (!is_int($data->duration)) return $this->json(['status'=> 400,
                                                            'message'=> 'Duration mut be a integer'],400);
        
        if (empty($data->title))return $this->json(['status'=> 400,
                                                        'message'=> 'title is required'],400);
                
        $movie = new Movie();
        $movie->setDuration($data->duration);
        $movie->setTitle($data->title);
        
        $em->persist($movie);
        $em->flush();

        return $this->json(['status'=>200,'message'=>'Video successfully added', 'data'=>$movie],200);
        

    }


    /**
     * @Route("api/movie/{id}", name="movie_update",methods={"PUT"})
     */
    public function update(Request $request,
                          MovieRepository $movieRepository,  
                          EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }                      

        $data = json_decode($request->getContent());

        if (empty($data)) return $this->json(['status'=> 400,'message'=> 'The content of the request empty']);  

        if (empty($data->duration)) return $this->json(['status'=> 400,
                                                            'message'=> 'Duration is required'
                                                        ],400);  
        
        if (!is_int($data->duration)) return $this->json(['status'=> 400,
                                                            'message'=> 'Duration mut be a integer'],400);
        
        if (empty($data->title))return $this->json(['status'=> 400,
                                                        'message'=> 'title is required'],400);                    

        $movie = $movieRepository->find($request->get('id'));

        if (empty($movie)) {
            return $this->json(['status'=>400,'message'=>'video not found'],400); 
        }
        
        $movie->setTitle($data->title);
        $movie->setDuration($data->duration);


        $em->persist($movie);
        $em->flush();

        $cache = new FilesystemAdapter('', 0, "cache");
        $cache->delete('movie_'.$movie->getId());

        return $this->json(['status'=>200,'message'=>'Video successfully updated', 'data'=>$movie],200);
        
    }


    /**
     * @Route("api/movie/{id}", name="movie",methods={"GET"})
     */
    public function show(Request $request,
                         MovieRepository $movieRepository,
                         MovieHasPeopleRepository $movieHasPeopleRepository,
                         PeopleRepository $peopleRepository,
                         MovieHasTypeRepository $movieHasTypeRepository,
                         TypeRepository $typeRepository) {

        $cache = new FilesystemAdapter('', 0, "cache");
        
        $movie = $cache->get('movie_'.$request->get('id'),function() use (
                $request,
                $movieRepository,
                $movieHasPeopleRepository,
                $peopleRepository,
                $movieHasTypeRepository,
                $typeRepository) {
                        
                $movie = $movieRepository->find($request->get('id'));
                if (empty($movie)) {
                    return null;
                }

                // affiache des acteurs / réalisateurs etc. ...
                $movieHasPeoples = $movieHasPeopleRepository->findBy(['movie_id'=>$movie->getId()]);
                $arrayPeoples = array();
                foreach($movieHasPeoples as $moviePeople) {
                    $arrayPeoples[] = $peopleRepository->find($moviePeople->getPeopleId());
                }

                // affichage des types
                $movieHasTypes = $movieHasTypeRepository->findBy(['movie_id'=>$movie->getId()]);
                $arrayTypes = array();
                foreach($movieHasTypes as $movieType) {
                    $arrayTypes[] = $typeRepository->find($movieType->getTypeId());
                }

                $movie->setPeoples($arrayPeoples);
                $movie->setTypes($arrayTypes);

                // image
                $movie->setUrlImage(RapidApiService::getUrlImage($movie->getTitle()));
                
                return $movie;

        });

        
        if (empty($movie)) {
            return $this->json(['status'=>400,'message'=>'video not found'],400); 
        }

        return $this->json($movie,200);
    }


    /**
     * @Route("api/movie/{id}", name="movie_delete",methods={"DELETE"})
     */
    public function delete(Request $request,
                         MovieRepository $movieRepository,
                         EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }  
                            
        $movie = $movieRepository->find($request->get('id'));
        
        if (empty($movie)) {
            return $this->json(['status'=>400,'message'=>'video not found'],400); 
        }

        $em->remove($movie);
        $em->flush();

        $cache = new FilesystemAdapter('', 0, "cache");
        $cache->delete('movie_'.$request->get('id'));

        return $this->json(['status'=>200,'message'=>'video deleted successfully'],200);
    }



}
