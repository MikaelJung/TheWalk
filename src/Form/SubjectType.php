<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
            'required' => true,
            'attr' => ['class' =>'form-control'],
            'label' => 'Titre'
            ])
            ->add('description', TextareaType::class, [
            'required' => true,
            'attr' => ['class' =>'form-control'],
            'label' => 'Description'
            ])
            ->add('ajouter', SubmitType::class, [
            'attr' => ['class' =>'form-control'],
            'label' => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
