<?php

namespace App\Form;

use App\Entity\ContactField;
use App\Enum\ContactFieldTypeEnum;
use App\Form\DataTransformer\ArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom du champ (affiché aux utilisateurs)',
                'attr' => [
                    'placeholder' => 'Ex: Adresse e-mail, Téléphone, Message...',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Clé technique (identifiant interne)',
                'attr' => [
                    'placeholder' => 'Ex: email, phone, message...',
                ],
                'help' => 'Utilisé dans le code — évite les espaces ou caractères spéciaux.',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de champ',
                'choices' => array_combine(
                    array_map(fn($e) => ucfirst(strtolower($e->name)), ContactFieldTypeEnum::cases()),
                    ContactFieldTypeEnum::cases()
                ),
                'placeholder' => '— Sélectionner un type —',
                'help' => 'Choisis le type de champ (texte, email, select, textarea...)',
            ])
            ->add('options', TextType::class, [
                'label' => 'Options (pour les listes déroulantes)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Oui, Non, Peut-être',
                ],
                'help' => 'Saisis plusieurs options séparées par des virgules. Laisse vide si non applicable.',
            ])
            ->add('isRequired', CheckboxType::class, [
                'label' => 'Champ obligatoire',
                'required' => false,
            ])
            ->add('orderIndex', IntegerType::class, [
                'label' => 'Ordre d’affichage',
                'required' => false,
                'attr' => [
                    'min' => 1,
                    'placeholder' => 'Ex: 1 pour le premier champ',
                ],
                'help' => 'Définit la position du champ dans le formulaire.',
            ])
        ;

        // Transformer pour convertir la chaîne "A, B, C" en ["A", "B", "C"]
        $builder->get('options')->addModelTransformer(new ArrayToStringTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactField::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'contact_field',
        ]);
    }
}
