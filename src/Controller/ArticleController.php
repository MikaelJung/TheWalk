<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Subject;
use App\Form\MessageType;
use App\Form\SubjectType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
* @Route("/articles")
*/
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles_index")
     */
    public function index(SubjectRepository $subjects)
    {
        return $this->render('article/index.html.twig', [
            'subjects' => $subjects->getAll(),
        ]);
    }



/**
 * @Route("/article/add", name="add_subject")
 *  @IsGranted("ROLE_USER")
 */
public function add_Subject(Subject $subject = null, Request $request, UserRepository $user)
{

    if(!$subject){
        $subject = new Subject();
    }

    $user = $this->getUser();
    
    $subject->setUser($user);
    $subject->setBlock(0);

    $form = $this->createForm(SubjectType::class, $subject);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
      
        $entityManager->persist($subject);
        $entityManager->flush();

        $this->addFlash('message', 'Article ajouté avec succès');
        return $this->redirectToRoute('articles_index');
    }
    
    return $this->render('article/add_subject.html.twig', [
        'subjectForm' => $form->createView(),
    ]);
}


   
    /**
     * @Route("/article/{id}", name="article_show", methods={"GET","POST"})
     *  @IsGranted("ROLE_USER")
     */

    public function show(Subject $subject, MessageRepository $messages, Request $request, UserRepository $user, $id): Response {

    $message = new Message();

    $user = $this->getUser();

    $em = $this->getDoctrine()->getManager();
    $subjectRepo = $em->getRepository(Subject::class);

    $idSubject= $subjectRepo->find($id);

    $message->setUser($user);
    $message->setSubject($idSubject);
    $message->setDateAdd(new \DateTime('now'));

    $form = $this->createForm(MessageType::class, $message);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
      
        $entityManager->persist($message);
        $entityManager->flush();

        $this->addFlash('message', 'Article ajouté avec succès');
        return $this->redirectToRoute('article_show',['id'=>$id]);
    }


         return $this->render('article/show.html.twig', 
         ['subject'=>$subject, 
         'messages' => $messages->findBySubject(
             array($id), 
             array('date_add' => 'DESC')
           ),
           'id'=>$id,
         'messageForm' => $form->createView()
         ]);
     }




    /**
    * @Route("/delete_message/{id}", name="delete_message")
    */
    public function deleteMessage($id)
    {
      $em = $this->getDoctrine()->getManager();
      $messageRepo = $em->getRepository(Message::class);
      $message = $messageRepo->find($id);

     
     
      $em->remove($message);
      $em->flush();

      return $this->redirectToRoute('articles_index');
      
    
    
     

    }






}
