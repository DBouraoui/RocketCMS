<?php

namespace App\Form;

use App\Entity\ReminderPhone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ReminderPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new NotBlank(message: 'Votre numéro de téléphone n\'est pas valide'),
                    new Regex('/^(0[467](?:\d{2}[- ]?){4})$/', message: 'Veuillez saisir un numéro de téléphone valide')
                ],
                'attr' => [
                    'placeholder' => '+33 / 06'
                ]
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Votre nom',
                'constraints' => [
                    new NotBlank(message: 'Votre nom n\'est pas valide'),
                    new Length(['max'=>40], maxMessage: 'Votre nom n\'est pas valide')
                ]
            ])
            ->add('reason', TextType::class, [
                'required' => false,
                'label'=> 'La raison de votre demande de rappel',
                'constraints' => [
                    new NotBlank(message: 'Votre raison de rappel n\'est pas valide'),
                    new Length(['max'=>255], maxMessage: 'Votre raison de rappel n\'est pas valide')
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReminderPhone::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'reminder_phone_item',
        ]);
    }
}
