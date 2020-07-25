<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
        
    ->add('firstname',TextType::class, [
        'attr' => ['class' =>'form-control'],
        'label' => 'Prénom',
    ])
    ->add('lastname',TextType::class, [
        'attr' => ['class' =>'form-control'],
        'label' => 'Nom',
    ])   
    ->add('pseudo',TextType::class, [
        'attr' => ['class' =>'form-control'],
        'label' => 'Pseudo',
    ])      
    ->add('avatar', FileType::class, [
                'data_class' => null,
                'required'   => false,
                'attr' => ['class' =>'form-control'],
            ])
    ->add('valider', SubmitType::class)
;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

