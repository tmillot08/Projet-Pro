<?php

namespace App\Controller;

use App\Entity\Jury;
use App\Entity\Admin;
use App\Form\AdminType;
use App\Entity\Secretary;
use App\Form\ResetPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="login")
     */
    public function login()
    { 
        return $this->render('security/login.html.twig');


    }
    
    /**
     * @Route("/redirectPassword", name="redirectPassword")
     */
    
    public function redirectPassword()
    {
      //get current session
      $user = $this-> getUser();
      //get value of firstLogin
      $session = $user-> getFirstlogin();
      //get Role user
      $role = $user->getRoles();

      //check session for first login
      if($session == 1){
        return $this->redirectToRoute('resetPassword');
      }else{
        //redirects to the right interface
        if($role[0] == 'ROLE_SECRETARY'){
        return $this->redirectToRoute('addUser'); 
        }elseif($role[0] == 'ROLE_JURY'){
          return $this->redirectToRoute('listJuryUser');
        }elseif($role[0] == 'ROLE_ADMIN'){
          return $this->redirectToRoute('accueilAdmin');
        }else{
          return $this->redirectToRoute('logout');
        }
      }
    }

    /**
     * @Route("/resetPassword", name="resetPassword")
     */
    
    public function resetPassword(Request $request,ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        // get current session
        $user = $this->getUser();
        // get Role user
        $role = $user->getRoles();
        // get Id user
        $id = $user->getId();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          if($role[0] == "ROLE_SECRETARY"){
          //find user by id in the repository
          $secretary=$manager->getRepository(Secretary::class)->find($id);
          //get new password
          $password = $form->get('password')->getData();
          //encode password
          $hash = $encoder->encodePassword($secretary, $password);
          $secretary->setPassword($hash);
          $secretary->setfirstlogin(0); 
          $manager->persist($secretary);
          $manager->flush();
          $this-> addflash(
            'success',
            "Votre mot de passe a été modifié"
          );
          
         
          }elseif($role[0] == "ROLE_JURY"){
            $jury=$manager->getRepository(Jury::class)->find($id);
            $password = $form->get('password')->getData();
            $hash = $encoder->encodePassword($jury, $password);
            $jury->setPassword($hash);
            $jury->setfirstlogin(0); 
            $manager->persist($jury);
            $manager->flush();
            $this-> addflash(
              'success',
              "Votre mot de passe a été modifié"
            );
          }else{
            $this-> addflash(
              'error',
              "Vous n'avez pas accés a cette page"

            );
            return $this->redirectToRoute('logout');
          }
          return $this->redirectToRoute('redirectPassword'); 
          
        }

        return $this->render('security/resetPassword.html.twig',[
          'ResetForm' => $form->createView()
        ]);

    }

    /**
     * @Route("/deconnexion", name="logout")
     */

    public function logout()
    {

    }

    /**
     * @Route("/GestionAdmin", name="gestionAdmin")
     */
    public function admin(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
      $admin = new Admin();
      $form = $this->createForm(AdminType::class, $admin);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
        $password = $admin->getPassword();
        $hash = $encoder->encodePassword($admin, $password);
        $admin->setPassword($hash);
        $manager->persist($admin);
        $manager->flush();
      };

      // return $this->render('security/admin.html.twig',[
      // 'form' => $form->createView()
      //]);
    
    }

}
