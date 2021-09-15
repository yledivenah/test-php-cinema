<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PeopleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;
use App\Entity\People;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\CustomService\AuthentificationService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class PeopleController extends AbstractController
{
    /**
     * @Route("api/peoples", name="peoples_index",methods={"GET"})
     */
    public function index(Request $request, PeopleRepository $peopleRepository) : JsonResponse
    {
        $page = $request->query->get('page') ?? 1;
        $people_per_page = $this->getParameter('people_per_page');
        $peoples = $peopleRepository->findAllByPage($page,$people_per_page);
        return $this->json($peoples,200);
        
    }

    /**
     * @Route("api/peoples", name="peoples_store",methods={"POST"})
     */
    public function store(Request $request,
                          EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        } 

        $data = json_decode($request->getContent());

        if (empty($data)) return $this->json(['status'=> 400,'message'=> 'The content of the request empty']);  
       
        if (empty($data->lastname)) return $this->json(['status'=> 400,
                                                            'message'=> 'lastname is required'
                                                        ],400);  
        
        if (empty($data->firstname)) return $this->json(['status'=> 400,
                                                            'message'=> 'firstname is required'],400);
                                
        if (empty($data->date_of_birth)) return $this->json(['status'=> 400,
                                                        'message'=> 'date of birth is required'],400);
                                                       
        $dateOfBirth = \DateTime::createFromFormat('Y-m-d', $data->date_of_birth);
        if (!$dateOfBirth) return $this->json(['status'=> 400,'message'=> 'incorrect date of birth'],400);
               
        if (empty($data->nationality)) return $this->json(['status'=> 400,'message'=> 'date of birth is required'],400);
                
        $people = new People();
        $people->setLastName($data->lastname);
        $people->setFirstName($data->firstname);
        $people->setDateOfBirth($dateOfBirth);
        $people->setNationality($data->nationality);
        
        $em->persist($people);
        $em->flush();

        return $this->json(['status'=>200,'message'=>'People successfully added', 'data'=>$people],200);
        

    }


    /**
     * @Route("api/people/{id}", name="people_update",methods={"PUT"})
     */
    public function update(Request $request,
                          PeopleRepository $peopleRepository,  
                          EntityManagerInterface $em) {
        
        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }

        $data = json_decode($request->getContent());

        if (empty($data)) return $this->json(['status'=> 400,'message'=> 'The content of the request empty']);  
        
        if (empty($data->lastname)) return $this->json(['status'=> 400,
                                                            'message'=> 'lastname is required'
                                                        ],400);  
        
        if (empty($data->firstname)) return $this->json(['status'=> 400,
                                                            'message'=> 'firstname is required'],400);
                                
        if (empty($data->date_of_birth)) return $this->json(['status'=> 400,
                                                        'message'=> 'date of birth is required'],400);
                                                        
        $dateOfBirth = \DateTime::createFromFormat('Y-m-d', $data->date_of_birth);
        if (!$dateOfBirth) return $this->json(['status'=> 400,'message'=> 'incorrect date of birth'],400);
                
        if (empty($data->nationality)) return $this->json(['status'=> 400,'message'=> 'date of birth is required'],400);
                
        $people = $peopleRepository->find($request->get('id'));
        
        if (empty($people)) {
            return $this->json(['status'=>400,'message'=>'people not found'],400); 
        }

        $people->setLastName($data->lastname);
        $people->setFirstName($data->firstname);
        $people->setDateOfBirth($dateOfBirth);
        $people->setNationality($data->nationality);
        
        $em->persist($people);
        $em->flush();

        $cache = new FilesystemAdapter('', 0, "cache");
        $cache->delete('people_'.$request->get('id'));

        return $this->json(['status'=>200,'message'=>'People successfully updated', 'data'=>$people],200);
        
    }


    /**
     * @Route("api/people/{id}", name="people",methods={"GET"})
     */
    public function show(Request $request,
                         PeopleRepository $peopleRepository) {

        $cache = new FilesystemAdapter('', 0, "cache");

        $people = $cache->get('people_'.$request->get('id'), function() use ($request, $peopleRepository){
            $people = $peopleRepository->find($request->get('id'));
        });

        if (empty($people)) {
            return $this->json(['status'=>400,'message'=>'People not found'],400); 
        }
    
        return $this->json($people,200);
    }


    /**
     * @Route("api/people/{id}", name="people_delete",methods={"DELETE"})
     */
    public function delete(Request $request,
                         PeopleRepository $peopleRepository,
                         EntityManagerInterface $em) {

        if (!AuthentificationService::isLogged())  {
            return $this->json(['status'=> 401,'message'=> 'unauthorized']);  
        }

        $people = $peopleRepository->find($request->get('id'));
        
        if (empty($people)) {
            return $this->json(['status'=>400,'message'=>'People not found'],400); 
        }

        $em->remove($people);
        $em->flush();

        $cache = new FilesystemAdapter('', 0, "cache");
        $cache->delete('people_'.$request->get('id'));

        return $this->json(['status'=>200,'message'=>'People deleted successfully'],200);
    }
}
