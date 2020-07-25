<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\UploadType;
use App\Form\ContactType;
use App\Form\ResetPassType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Security\UsersAuthenticator;
use App\Notification\ContactNotification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Notification\CreationCompteNotification;
use App\Notification\ActivationCompteNotification;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

    /**
    * @Route("/security")
    */

class SecurityController extends AbstractController
{

    /**
     * @var CreationCompteNotification
     */
    private $notify_creation;

    /**
     * @var ActivationCompteNotification
     */
    private $notify_activation;



    public function __construct(CreationCompteNotification $notify_creation, ActivationCompteNotification $notify_activation)
    {
        $this->notify_creation = $notify_creation;
        $this->notify_activation = $notify_activation;
    }


    /**
     * @Route("/register", name="security_registration") 
    */   
    public function registration(Request $request, ManagerRegistry $manager, UserPasswordEncoderInterface $encoder,GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator ){

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                $user = $form->getData();
                $user->setActivationToken(md5(uniqid()));
                $entityManager = $this->getDoctrine()->getManager();
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $entityManager->persist($user);
                $entityManager->flush();

                 $this->notify_creation->notify();

            // Envoie le mail d'activation
            $this->notify_activation->notify($user);
            return $this->redirectToRoute('security_login');


            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'main' // firewall name in security.yaml
            // );
        

        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }




     /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $usersRepo){
        // On vérifie si un utilisateur a ce token
        $user = $usersRepo->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'existe avec ce token
        if(!$user){
            // Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActivationToken(null);
        $user->setRoles(['ROLE_USER']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On envoie un message flash
        $this->addFlash('message', 'Vous avez bien activé votre compte');

        // On retoure à l'accueil
        return $this->redirectToRoute('home');
    }





    /**
     * @Route("/login", name="security_login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils){

        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig',[
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);

    }





    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){

    }



/**
     * @Route("/profile", name="security_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(){


         return $this->render('security/profile.html.twig');
    }







    /**
     * @Route("/profile_edit/{id}", name="security_profile_edit")
     * @IsGranted("ROLE_USER")
     */
    public function profileEdit(Request $request, UserRepository $user){

        $user = $this->getUser();
        $currentAvatar = $user->getAvatar();

        if(!empty($currentAvatar)){

            $avatarPath = ($this->getParameter('upload_directory') . DIRECTORY_SEPARATOR . $user->getAvatar());
           
        }

        $form = $this->createForm(UploadType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $user->getAvatar();

            if(!is_null($file)){
                $file = $user->getAvatar();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $user->setAvatar($fileName);
            }

            else {
                $user->setAvatar($currentAvatar);
            }

            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
           

            return $this->redirectToRoute('security_profile');
        }


         return $this->render('security/profileEdit.html.twig', [
             'UploadForm' => $form->createView()
         ]);
    }





    



    
     /**
     * @Route("/contact", name="security_contact")
     */
    public function contact(Request $request, ContactNotification $notification): Response
    {

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $notification->notify($contact);
            $this->addFlash('success','Votre email a bien été envoyé');
            return $this->redirectToRoute('home');
        }   



        return $this->render('security/contact.html.twig',[
            'ContactForm'=>$form->createView()
        ]);
    }




       /**
     * @Route("/forgotten-pass", name="forgotten_password")
     */

     public function forgottenPass(Request $request, UserRepository $usersRepo, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator){

        $form = $this->createForm(ResetPassType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $donnees = $form->getData();
            $user = $usersRepo->findOneByEmail($donnees['email']);

            if(!$user){
                $this->addFlash('danger','Cette adresse n\'existe pas');
                $this->redirectToRoute('security_login');
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch(\Exception $e){
                $this->addFlash('warning', 'Une erreur est survenue : '. $e->getmessage());
                return $this->redirectToRoute('security_login');
            }

            $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('test@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                "<p>Bonjour,</p><p> Une demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant : ".$url.'</p>', 'text/html'
            );

            $mailer->send($message);

            $this->addFlash('message', 'Un email de réinitialisation de mot de passe vous a été envoyé');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/forgotten_password.html.twig', ['emailForm'=> $form->createView()]);

     

     }



     /**
     * @Route("/reset-pass/{token}", name="reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        if(!$user){
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('security_login');
        }

        // Si le formulaire est envoyé en méthode POST
        if($request->isMethod('POST')){
            // On supprime le token
            $user->setResetToken(null);

            // On chiffre le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Mot de passe modifié avec succès');

            return $this->redirectToRoute('security_login');
        }else{
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }
        


    }




    /**
     * @Route("/delete_user/{id}", name="delete_user")
     */
    public function deleteUser($id)
    {
      $em = $this->getDoctrine()->getManager();
      $usrRepo = $em->getRepository(User::class);

      $user = $usrRepo->find($id);

      if($this->getUser() === $user) {
      $em->remove($user);
      $em->flush();
      return $this->redirectToRoute('security_login');
      }

      return $this->redirectToRoute('error404');
     

    }



    /**
     * @Route("/login_success", name="login_success")
     */
   public function loginRedirectAction(Request $request)
{
    if($this->isGranted('ROLE_ADMIN'))
    {
        return $this->redirectToRoute('admin');
    }
    else if($this->isGranted('ROLE_USER'))
    {
         $this->addFlash('success', 'Bravo, vous vous êtes bien connecté !');
        return $this->redirectToRoute('home');
    }
    else if($this->isGranted('ROLE_ANONYME'))
    {
        $this->addFlash('success', 'Veuillez activer votre compte pour vous connecter!');
        return $this->redirectToRoute('security_login');
         
    }
}





    /**
     * @Route("/error404", name="error404")
     */
    public function error404()
    {

         return $this->render('security/error404.html.twig');

    }






}
