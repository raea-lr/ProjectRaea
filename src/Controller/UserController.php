<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /*Affichage des Users*/
    /**
     * @param UserRepository $repository
     * @return Response
     * @Route("/listUser",name="listUser")
     */
    public function list(){
        $entityManger= $this->getDoctrine();
        $list=$entityManger->getRepository(User::class)->findAll();
        return $this->render('user/afficheUser.html.twig',
            ['list'=>$list]);
    }


    /*Ajout des Users*/
    /**
     * @Route("/addUser", name="addUser")
     */
    public function addUser(Request $request){

        $u= new User();
        $form= $this->createForm(UserType::class, $u);
        $form= $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $entityManger=$this->getDoctrine()->getManager();
            $entityManger->persist($u);
            $entityManger->flush();
            return $this->redirectToRoute('listUser');
        }
        return $this->render('user/addUser.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /*Modification des Users*/
    /**
     * @param $id
     * @param UserRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/updateUser/{id}",name="updateUser")
     */
    function update($id,UserRepository $repository,\Symfony\Component\HttpFoundation\Request $request){
        $USER=$repository->find($id);
        $form=$this->createForm(UserType::class,$USER);
        //$form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheUser");


        }
        return $this->render('user/updateUser.html.twig',
            [
                'f'=>$form->createView()
            ]);
    }

    /*Suppression des Users*/
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/suppUser/{id}",name="suppUser")
     */
    function delete($id){
        $repo=$this->getDoctrine()->getRepository(User::class);
        $USER=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($USER);
        $em->flush();
        return $this->redirectToRoute('afficheUser');
    }



}
