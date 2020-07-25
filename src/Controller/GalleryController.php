<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
* @Route("/gallery")
*/
class GalleryController extends AbstractController
{
  

    /**
     * @Route("/", name="gallery_index")
     */
    public function pictureList(PictureRepository $pictures)
    {
        return $this->render('gallery/index.html.twig', [
            'pictures' => $pictures->findAll(),
        ]);
    }



     /**
     * @Route("/member_gallery", name="private_gallery_index")
     * @IsGranted("ROLE_MEMBER")
     */
    public function privatePictureList(PictureRepository $pictures)
    {
        return $this->render('gallery/private.html.twig', [
            'pictures' => $pictures->findAll(),
        ]);
    }





}
