<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjetController extends AbstractController
{
    /**
     * @Route("/projet", name="projet")
     */
    public function index(): Response
    {
        return $this->render('projet/index.html.twig', [
            'controller_name' => 'ProjetController',
        ]);
    }

    /*Affichage des Projets*/
    /**
     * @param ProjetRepository $repository
     * @return Response
     * @Route("/listProjet",name="listProjet")
     */
    public function list(){
        $entityManger= $this->getDoctrine();
        $list=$entityManger->getRepository(Projet::class)->findAll();
        return $this->render('projet/afficheProjet.html.twig',
            ['list'=>$list]);
    }


    /*Ajout des Projets*/
    /**
     * @Route("/addProjet", name="addProjet")
     */
    public function addUser(Request $request){

        $P= new User();
        $form= $this->createForm(ProjetType::class, $P);
        $form= $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $entityManger=$this->getDoctrine()->getManager();
            $entityManger->persist($P);
            $entityManger->flush();
            return $this->redirectToRoute('listUser');
        }
        return $this->render('projet/addProjet.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /*Modification des Projets*/
    /**
     * @param $id
     * @param ProjetRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/updateProjet/{id}",name="updateProjet")
     */
    function update($id,ProjetRepository $repository,\Symfony\Component\HttpFoundation\Request $request){
        $USER=$repository->find($id);
        $form=$this->createForm(ProjetType::class,$USER);
        //$form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheProjet");


        }
        return $this->render('projet/updateProjet.html.twig',
            [
                'f'=>$form->createView()
            ]);
    }

    /*Suppression des Projets*/
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/suppProjet/{id}",name="suppProjet")
     */
    function delete($id){
        $repo=$this->getDoctrine()->getRepository(Projet::class);
        $Pro=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Pro);
        $em->flush();
        return $this->redirectToRoute('afficheProjet');
    }


}
