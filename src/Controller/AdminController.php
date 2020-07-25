<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Entity\Picture;
use App\Entity\Subject;
use App\Form\PictureType;
use App\Form\EditUserType;
use App\Form\EditMemberType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\PictureRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * @Route("/admin")
     */

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }




    /**
     * @Route("/users", name="users")
     */
    public function usersList(UserRepository $users)
    {
        return $this->render('admin/users/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }



/**
 * @Route("/users/edit/{id}", name="edit_user")
 */
public function editUser(User $user, Request $request)
{
    $form = $this->createForm(EditUserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Utilisateur modifié avec succès');
        return $this->redirectToRoute('users');
    }
    
    return $this->render('admin/users/editUser.html.twig', [
        'userForm' => $form->createView(),
    ]);
}





    /**
     * @Route("/tasks", name="tasks")
     */
    public function tasksList(TaskRepository $tasks)
    {
        return $this->render('admin/tasks/tasks.html.twig', [
            'tasks' => $tasks->findAll(),
        ]);
    }



/**
 * @Route("/tasks/add", name="add_task")
 * @Route("/tasks/edit/{id}", name="edit_task")
 */
public function add_edit_Task(Task $task = null, Request $request)
{

    if(!$task){
        $task = new Task();
    }

    $form = $this->createForm(TaskType::class, $task);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        $this->addFlash('message', 'Fonction modifié avec succès');
        return $this->redirectToRoute('tasks');
    }
    
    return $this->render('admin/tasks/add_edit_task.html.twig', [
        'taskForm' => $form->createView(),
        'editMode' => $task->getId() !== null
    ]);
}


 /**
     * @Route("/tasks_delete/{id}", name="delete_task")
     */
    public function deleteTask($id)
    {
      $em = $this->getDoctrine()->getManager();
      $taskRepo = $em->getRepository(Task::class);

      $task = $taskRepo->find($id);
      $em->remove($task);
      $em->flush();

      return $this->redirectToRoute('tasks');

    }






/**
     * @Route("/members", name="members")
     */
    public function membersList(UserRepository $users)
    {


        return $this->render('admin/members/members.html.twig', [
            'users' => $users->getAll(),
        ]);
    }



/**
 * @Route("/members/edit/{id}", name="edit_member")
 */
public function editMember(User $user, Request $request)
{

    $form = $this->createForm(EditMemberType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Membre modifié avec succès');
        return $this->redirectToRoute('members');
    }
    
    return $this->render('admin/members/editMember.html.twig', [
        'memberForm' => $form->createView(),
        
    ]);
}




    /**
     * @Route("/gallery", name="gallery")
     */
    public function galleryList(PictureRepository $pictures)
    {
        return $this->render('admin/gallery/gallery.html.twig', [
            'pictures' => $pictures->findAll(),
        ]);
    }


/**
 *  * @Route("/picture/add", name="add_picture", methods="GET|POST")
 * @Route("/picture/edit/{id}", name="edit_picture")
 */
public function editGallery(Picture $picture = null, Request $request, SluggerInterface $slugger)
{

     if(!$picture){
        $picture = new Picture();
        
    }

    $form = $this->createForm(PictureType::class, $picture);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
    /** @var UploadedFile $pic */
        $pic = $form->get('link')->getData();

        if ($pic) {
                $originalFilename = pathinfo($pic->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pic->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pic->move(
                        $this->getParameter('upload_gallery_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'picname' property to store the PDF file name
                // instead of its contents
                $picture->setLink($newFilename);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($picture);
            $entityManager->flush();

        $this->addFlash('message', 'Membre modifié avec succès');
        return $this->redirectToRoute('gallery');
    }
    
    return $this->render('admin/gallery/add_edit_picture.html.twig', [
         'pictureForm' => $form->createView(),
         'editMode' => $picture->getId() !== null
        
    ]);
}





  /**
     * @Route("/delete_picture/{id}", name="delete_picture")
     */
    public function deletePicture($id)
    {
      $em = $this->getDoctrine()->getManager();
      $picRepo = $em->getRepository(Picture::class);

      $pic = $picRepo->find($id);
      $em->remove($pic);
      $em->flush();

      return $this->redirectToRoute('gallery');

    }





     /**
     * @Route("/articles", name="articles")
     */
    public function articlesList(SubjectRepository $subjects)
    {
        return $this->render('admin/articles/subjects.html.twig', [
            'subjects' => $subjects->findAll(),
        ]);
    }



    /**
     * @Route("/delete_subject/{id}", name="delete_subject")
     */
    public function deleteSubject($id)
    {
      $em = $this->getDoctrine()->getManager();
      $subjectRepo = $em->getRepository(Subject::class);

      $subject = $subjectRepo->find($id);
      $em->remove($subject);
      $em->flush();

      return $this->redirectToRoute('articles');

    }




}
