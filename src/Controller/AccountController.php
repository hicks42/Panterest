<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("", name="app_account", methods="GET")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        // if(! $this->getUser()){
        //     $this->addFlash('error', 'Please, log in first !');
        //     return $this->redirectToRoute('app_login');
        // }
        
        return $this->render('account/show.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

     /**
     * @Route("/edit", name="app_edit_account", methods={"GET","PATCH"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function editAccount(Request $request, EntityManagerInterface $em): Response
    {
        // if(! $this->getUser()){
        //     $this->addFlash('error', 'Please, log in first !');
        //     return $this->redirectToRoute('app_login');
        // }

        $user = $this->getUser();
        $form=$this->createForm(UserFormType::class, $user, [
            'method' => 'PATCH'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Account successfully updated');
            return $this->redirectToRoute('app_account');
        }
            return $this->render('account/edit.html.twig', [
            'editform'=> $form->createView()
            ]);
    }

    /**
     * @Route("/change-password", name="app_change_password", methods={"GET","PATCH"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // if(! $this->getUser()){
        //     $this->addFlash('error', 'Please, log in first !');
        //     return $this->redirectToRoute('app_login');
        // }

        $user = $this->getUser();
        $form=$this->createForm(ChangePasswordFormType::class, null, [
            'method' => 'PATCH',
            'current_password_required'=>true
        ]);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form['plainPassword']->getData())
            );
          
            $em->flush();
    
            $this->addFlash('success', 'Password successfully updated');
    
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/change.password.html.twig', [
            'form'=> $form->createView()
            ]);
    }
}
