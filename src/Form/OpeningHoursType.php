<?php

namespace App\Form;

use App\Entity\OpeningHours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeningHoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day', TextType::class, [
                'label' => 'Jour',
                'attr' => [
                    'class' => 'input w-full border border-neutral-300 rounded-md py-2 px-3 bg-neutral-100 focus:ring-0 focus:border-black',
                    'readonly' => true,
                ],
            ])
            ->add('openMorning', TimeType::class, [
                'label' => 'Ouverture matin',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => [
                    'class' => 'input w-full border border-neutral-300 rounded-md py-2 px-3 focus:ring-0 focus:border-black',
                    'step' => 900, // pas de 15 min
                ],
                'required' => false,
            ])
            ->add('closeMorning', TimeType::class, [
                'label' => 'Fermeture matin',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => [
                    'class' => 'input w-full border border-neutral-300 rounded-md py-2 px-3 focus:ring-0 focus:border-black',
                    'step' => 900,
                ],
                'required' => false,
            ])
            ->add('openAfternoon', TimeType::class, [
                'label' => 'Ouverture après-midi',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => [
                    'class' => 'input w-full border border-neutral-300 rounded-md py-2 px-3 focus:ring-0 focus:border-black',
                    'step' => 900,
                ],
                'required' => false,
            ])
            ->add('closeAfternoon', TimeType::class, [
                'label' => 'Fermeture après-midi',
                'widget' => 'single_text',
                'input' => 'string',
                'attr' => [
                    'class' => 'input w-full border border-neutral-300 rounded-md py-2 px-3 focus:ring-0 focus:border-black',
                    'step' => 900,
                ],
                'required' => false,
            ])
            ->add('isclosed', null, [
                'label' => 'Fermé ce jour',
                'attr' => [
                    'class' => 'rounded border-neutral-300 text-black focus:ring-0 focus:border-black',
                ],
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OpeningHours::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'opening_hours',
        ]);
    }
}
