<?php

namespace App\Form;

use App\Entity\Settings;
use App\Enum\ThemesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Titre du site web',
                    'constraints' => [
                        new NotBlank(message: 'Le titre est obligatoire'),
                        new Length(['max' => 30], maxMessage: 'Le titre ne peut dépasser 30 caractères'),
                    ],
                ])
            ->add('description',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Description du site web',
                    'constraints' => [
                        new NotBlank(message: 'La description ne doit pas être vide'),
                        new Length(['max'=>255], maxMessage: 'La description n\'est pas valide'),
                    ]
                ])
            ->add('contactEmail',
                EmailType::class,
                [
                    'required' => true,
                    'label' => 'Email de contact',
                    'constraints' => [
                        new NotBlank(message: "Vous devez entrer une adresse email valide"),
                        new Length(['max' => 255], maxMessage: "Vous devez entrer une adresse email valide"),
                        new Email(message: "Votre email n'est pas valide"),
                    ]
                ])
            ->add('contactPhone',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Numéro de téléphone de contact',
                    'constraints' => [
                        new NotBlank(message: 'Votre numéro de téléphone n\'est pas valide'),
                        new Regex('/^(0[467](?:\d{2}[- ]?){4})$/', message: 'Veuillez saisir un numéro de téléphone valide')
                    ]
                ],
            )
            ->add('logo', FileType::class,
                [
                    'required' => false,
                    'label' => 'Logo de votre site web',
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '6M',
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/svg+xml'],
                            'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, SVG)',
                            'maxSizeMessage' => 'Veuillez uploader une image maximum %s Mo',
                        ])
                    ]
                ])
            ->add('favicon', FileType::class,
                [
                    'required' => false,
                    'label' => 'Logo afficher sur Google',
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '4M',
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/svg+xml'],
                            'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, SVG)',
                            'maxSizeMessage' => 'Veuillez uploader une image maximum %s Mo',
                        ])
                    ]
                ]
            )
            ->add('theme');
        $builder->add('theme', ChoiceType::class, [
            'choices' => ThemesEnum::cases(),
            'choice_label' => fn(ThemesEnum $theme) => $theme->value,
            'choice_value' => fn(?ThemesEnum $theme) => $theme?->value,
            'label' => 'Thème du site',
            'required' => true,
            'disabled' => !$options['is_admin'], // Désactivé si pas admin
            'attr' => [
                'class' => $options['is_admin'] ? '' : 'hidden' // Caché si pas admin
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
            'is_admin' => false,
        ]);
    }
}
