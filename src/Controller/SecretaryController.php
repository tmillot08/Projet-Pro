<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Entity\Folder;
use App\Form\NoteType;
use App\Entity\Secretary;
use App\Form\AddUserType;
use App\Form\DossierType;
use App\Repository\UserRepository;
use App\Repository\SecretaryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecretaryController extends AbstractController
{
    /**
     * @Route("/secretary", name="addUser")
     */
    public function addUser( Request $request, ObjectManager $manager)
    {
        $role = $this->getUser()->getRoles();
        if($role[0] == "ROLE_SECRETARY"){
        $user = new User();

        $form = $this->createForm(AddUserType::class, $user);
        $folder = new Folder();
        $formfolder =  $this->createForm(DossierType::class, $folder);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($user);
            $manager->flush();
            $id = $user->getId();
            $formfolder->handleRequest($request);
            if($formfolder->isvalid()){
              
              $userid=$manager->getRepository(User::class)->find($id);
              $userid->getId();
              $folder->setUser($userid);
              dump($folder);
              die;
              $manager->persist($folder);
              $manager->flush();
            }
        }

        
        return $this->render('secretary/secretary.html.twig', [
            'addUserForm' => $form->createView(),
            'addFolderForm' => $formfolder->createView()
        ]);
        }else{
           return $this->redirectToRoute('redirectSecretary');
        }
    }

     /**
     * @Route("/secretary/users", name="listeUser")
     */
    public function listeUser(UserRepository $repo)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();
        return $this->render('secretary/listeUser.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/secretary/{id}/edituser", name="editUser")
     * 
     * @param User $user
     * @return Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AddUserType::class, $user);
        $folder = $manager->getRepository(Folder::class)->findOneBy([
            'user' => $user,
        ]);
        $formFolder = $this->createForm(DossierType::class, $folder);

        $form->handleRequest($request);
        $formFolder->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() && $formFolder->isSubmitted() && $formFolder->isValid()){
            $manager->persist($user, $folder);
            $manager->flush();
        }


        return $this->render('secretary/editUser.html.twig', [
            'user' => $user,
            'folder' => $folder,
            'addUserForm' => $form->createView(),
            'addFolderForm' => $formFolder->createView(),

        ]);
    }
    
     /**
     * @Route("/secretary/{id}/voir_utilisateur", name="showUser")
     * 
     * @param User $user
     * @return Response
     */

    public function show(User $user)
    {
        return $this->render('secretary/showUser.html.twig', [
            'user' => $user,
        ]);
    }
}
