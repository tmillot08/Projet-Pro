<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Entity\Folder;
use App\Form\NoteType;
use App\Entity\Secretary;
use App\Form\AddUserType;
use App\Form\DossierType;
use App\Service\Pagination;
use App\Repository\UserRepository;
use App\Repository\SecretaryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
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

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getPicture();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            // move file to the upload directory
            try {
                $file->move(
                    $this->getParameter('identity_directory'),
                    $fileName
                );
            }catch (FileException $e){
                // redirect with error message if something happens during upload
                $this-> addflash(
                    'error',
                    "Une erreur est survenue pendant l'upload"
                );
                return $this->redirectToRoute('addUser');
            }
            $user->setPicture($fileName);
            $manager->persist($user);
            $manager->flush();
            $id = $user->getId();
            $formfolder->handleRequest($request);
            if($formfolder->isSubmitted()){
              
              $userid=$manager->getRepository(User::class)->find($id);
              $userid->getId();
              $folder->setUser($userid);
              $manager->persist($folder);
              
              $manager->flush();
              $this-> addflash(
                'success',
                "le dossier à été ajouté"
              );
              return $this->redirectToRoute('listeUser');
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
     * @Route("/secretary/users/{page<\d+>?1}", name="listeUser")
     */
    public function listeUser(UserRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Folder::class)
                   ->setPage($page);
        return $this->render('secretary/listeUser.html.twig', [
            'pagination' => $pagination,
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
        if($user->getPicture() != Null){    
            $user->setPicture(
                new File($this->getParameter('identity_directory').'/'.$user->getPicture())
            );
            $fileuser= $user->getPicture()->getFilename();
        }
        
        $form = $this->createForm(AddUserType::class, $user);
        $folder = $manager->getRepository(Folder::class)->findOneBy([
            'user' => $user,
        ]);
        $formFolder = $this->createForm(DossierType::class, $folder);
        $form->handleRequest($request);
        $formFolder->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid() && $formFolder->isSubmitted()){
      
            if($user->getPicture() == Null){
               $user->setPicture($fileuser);
            }else{
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
             $file = $user->getPicture();
             $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        
            try {
            $file->move(
                $this->getParameter('identity_directory'),
                $fileName
            );
            }catch (FileException $e){
                $this-> addflash(
                'error',
                "Une erreur est survenue pendant l'upload"
            );
            return $this->redirectToRoute('addUser');
            }
            $user->setPicture($fileName);
        };
            $manager->persist($user, $folder);
            $manager->flush();
            $this-> addflash(
                'success',
                "le dossier à été modifié"
            );
            return $this->redirectToRoute('listeUser');
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

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
