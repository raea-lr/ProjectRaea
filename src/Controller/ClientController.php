<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    /*Affichage des Clients*/
    /**
     * @param ClientRepository $repository
     * @return Response
     * @Route("/listClient",name="listClient")
     */
    public function listClient(){
        $entityManger= $this->getDoctrine();
        $list=$entityManger->getRepository(Client::class)->findAll();
        return $this->render('client/afficheClient.html.twig',
            ['list'=>$list]);
    }


    /*Ajout des Clients*/
    /**
     * @Route("/addClient", name="addClient")
     */
    public function addClient(Request $request){

        $CL= new Client();
        $form= $this->createForm(ClientType::class, $CL);
        $form= $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $entityManger=$this->getDoctrine()->getManager();
            $entityManger->persist($CL);
            $entityManger->flush();
            return $this->redirectToRoute('listClient');
        }
        return $this->render('client/addClient.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /*Modification des Clients*/
    /**
     * @param $id
     * @param ClientRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/updateClient/{id}",name="updateClient")
     */
    function updateClient($id,ClientRepository $repository,\Symfony\Component\HttpFoundation\Request $request){
        $CLIENT=$repository->find($id);
        $form=$this->createForm(ClientType::class,$CLIENT);
        //$form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheClient");


        }
        return $this->render('client/updateClient.html.twig',
            [
                'f'=>$form->createView()
            ]);
    }

    /*Suppression des Clients*/
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/suppClient/{id}",name="suppClient")
     */
    function deleteClient($id){
        $repo=$this->getDoctrine()->getRepository(Client::class);
        $Cl=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Cl);
        $em->flush();
        return $this->redirectToRoute('afficheClient');
    }


}
