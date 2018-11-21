<?php

namespace App\Controller;

use App\Entity\Jury;
use App\Entity\User;
use App\Entity\Note;
use App\Entity\Folder;
use App\Entity\Session;
use App\Entity\Secretary;
use App\Form\SessionType;
use App\Form\RegistrationType;
use App\Form\RegistrationJuryType;
use App\Repository\JuryRepository;
use App\Repository\UserRepository;
use App\Repository\FolderRepository;
use App\Repository\SessionRepository;
use App\Repository\SecretaryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/administration/accueil", name="accueilAdmin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/administration/session", name="session")
     */
    public function session(SessionRepository $repo,Request $request, ObjectManager $manager)
    {
        $session = $repo-> findOneBy([
            'id' => 1
        ]);
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $session->setSessionNow(1);
            $manager->persist($session);
            $manager->flush();
        }
        return $this->render('admin/session.html.twig', [
            'session' => $session,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/administration/sessionclose", name="sessionclose")
     */
    public function sessionclose(SessionRepository $repo,Request $request, ObjectManager $manager)
    {
        $session = $repo-> findOneBy([
            'id' => 1
        ]);
           
        $session->setSessionNow(0);
        $manager->persist($session);
        $manager->flush();
        return $this->redirectToRoute('session');
    }


    /**
     * @Route("/administration/GestionSecretaire", name="gestionSecretary")
     */
    public function secretaire(SecretaryRepository $repo, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $listSecretary = $repo->findAll();
        $secretary  = new Secretary();
        $form = $this->createForm(RegistrationType::class, $secretary);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $mail = $secretary->getMail();
            $password =  $this->generatePassword();
            $message = (new \Swift_Message('Votre Mot de passe'))
            ->setFrom('thomas.millot08@gmail.com')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    array('password' => $password)
                ),
                'text/html'
            );
    
            $mailer->send($message);
            $hash = $encoder->encodePassword($secretary, $password);
            $secretary->setPassword($hash);
            $secretary->setFirstlogin(1);

            $manager->persist($secretary);
            $manager->flush();
            $this->redirectToRoute('gestionSecretary');
        }

        return $this->render('admin/GestionSecretaire.html.twig', [
            'form' => $form->createView(),
            'secretary' => $listSecretary
        ]);
    }

    /**
     * @Route("/administration/gestionUtilisateur", name="gestionUser")
     */
    public function user(FolderRepository $repo, Request $request, ObjectManager $manager)
    {
        $listFolder = $repo->findAll();

        return $this->render('admin/GestionUser.html.twig', [
            'folders' => $listFolder
        ]);
    }

    /**
     * @Route("/administration/gestionJury", name="gestionJury")
     */
    public function jury(JuryRepository $repo, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $listJury = $repo->findAll();
        $jury  = new Jury();
        $form = $this->createForm(RegistrationJuryType::class, $jury);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $mail = $jury->getMail();
            $password =  $this->generatePassword();
            $message = (new \Swift_Message('Votre Mot de passe'))
            ->setFrom('thomas.millot08@gmail.com')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    array('password' => $password)
                ),
                'text/html'
            );
    
            $mailer->send($message);
            $hash = $encoder->encodePassword($jury, $password);
            $jury->setPassword($hash);
            $jury->setFirstlogin(1);
            $now = new \DateTime();
            $jury->setCreatedAt($now);
            $manager->persist($jury);
            $manager->flush();
            $this->redirectToRoute('gestionJury');
        }

        return $this->render('admin/GestionJury.html.twig', [
            'form' => $form->createView(),
            'listJury' => $listJury        
        ]);
    }
    
    /**
     * @Route("/administration/{id}/editionSecretaire", name="editSecretary")
     * 
     * @param Secretary $secretary
     * @return Response
     */
    public function editSecretary(Secretary $secretary, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(RegistrationType::class, $secretary);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($secretary);
            $manager->flush();
        }
        return $this->render('admin/editSecretary.html.twig', [
            'secretary' => $secretary ,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/administration/{id}/supprimerSecretaire", name="deleteSecretary")
     * @param Secretary $secretary
     * @param ObjectManager $manager
     * @return Response
     */
    public function deleteSecretary(Secretary $secretary, ObjectManager $manager)
    {
        $manager->remove($secretary);
        $manager->flush();
        return $this->redirectToRoute('gestionSecretary');
    }
    
    /**
     * @Route("/administration/{id}/editionJury", name="editJury")
     * 
     * @param Jury $jury
     * @return Response
     */
    public function editJury(Jury $jury, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(RegistrationJuryType::class, $jury);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($jury);
            $manager->flush();
        }
        return $this->render('admin/editJury.html.twig', [
            'jury' => $jury ,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/administration/{id}/supprimerJury", name="deleteJury")
     * @param Jury $jury
     * @param ObjectManager $manager
     * @return Response
     */
    public function deleteJury(Jury $jury, ObjectManager $manager)
    {
        $manager->remove($jury);
        $manager->flush();
        return $this->redirectToRoute('gestionJury');
    }
    
    /**
     * @Route("/administration/{id}/Notation", name="shownote")
     * 
     * @param Folder $folder
     * @return Response
     */
    public function showNotation(Folder $folder, Request $request, ObjectManager $manager)
    {
        $note = $manager->getRepository(Note::class)->findBy([

            'folder' => $folder
        ]);
        return $this->render('admin/showNote.html.twig', [
            'note' => $note,
        ]);
    }
    
    /**
     * @Route("/administration/{id}/Dossier", name="showFolder")
     * 
     * @param Folder $folder
     * @return Response
     */
    public function showFolder(Folder $folder, Request $request, ObjectManager $manager)
    {
        return $this->render('admin/showFolder.html.twig', [
            'folder' => $folder,
        ]);
    }

    /**
     * @Route("/administration/{id}/supprimerUtilisateur", name="deleteUser")
     * @param User $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function deleteUser(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('gestionUser');
    }

    public function generatePassword($length = 8, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $PasswordLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $PasswordLength - 1)];
        }
        return $randomPassword;
    }
}

