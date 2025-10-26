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

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['required' => true, 'label' => 'Titre du site web'])
            ->add('description',TextType::class, ['required' => true, 'label' => 'Description du site web'])
            ->add('contactEmail',EmailType::class, ['required' => true, 'label' => 'Email de contact'])
            ->add('contactPhone',TextType::class, ['required' => true, 'label' => 'Numéro de téléphone de contact'])
            ->add('logo', FileType::class, ['required' => false, 'label' => 'Logo de votre site web'])
            ->add('favicon', FileType::class, ['required' => false, 'label' => 'Logo afficher sur Google'])
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
