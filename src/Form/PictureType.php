<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
    ->add('title', TextType::class,[
        'constraints' => [
            new NotBlank([
                'message' => 'Merci d\'entrer un titre',
            ]),
        ],
        'required' => true,
        'attr' => ['class' =>'form-control'],
         'label' => 'Titre', 
    ])
    ->add('link', FileType::class,[
                'label' => false,
                'mapped' => false,
                'required' => false,
                'attr' => ['class' =>'form-control'],
    ])
    ->add('valider', SubmitType::class)
;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}

