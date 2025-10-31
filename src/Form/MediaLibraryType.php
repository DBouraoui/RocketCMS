<?php

namespace App\Form;

use App\Entity\MediaLibrary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class MediaLibraryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'label' => 'Image',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotNull(message: 'L\'image est obligatoire'),
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Titre de l’image'],
                'required' => true,
                'constraints' => [
                    new NotNull(message: 'Le titre est obligatoire'),
                    new Length(['max' => 255], maxMessage: 'Le titre ne peut dépasser {{ limit }} caractères')
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'constraints' => [
                  new Length(['max'=>500], maxMessage: 'La description ne peut dépasser {{ limit}} caractères')
                ],
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Brève description ou contexte de l’image',
                    'data-controller'=> 'markdown-editor',
                    'id'=> 'markdown-editor'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MediaLibrary::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'media_library_item',
        ]);
    }
}
