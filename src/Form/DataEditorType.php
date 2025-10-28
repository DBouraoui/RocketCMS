<?php

namespace App\Form;

use App\Entity\MenuLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataEditorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var MenuLink $menuLink */
        $menuLink = $options['data'];

        $structure = $menuLink->getStructure() ?? [];
        $content = $menuLink->getContent() ?? [];

        foreach ($structure as $fieldName => $fieldOptions) {
            $label = $fieldOptions['label'] ?? ucfirst($fieldName);
            $helper = $fieldOptions['helper'] ?? null;
            $type = $fieldOptions['champ'] ?? 'text';

            // Déterminer le type Symfony
            switch ($type) {
                case 'textarea':
                    $fieldType = TextareaType::class;
                    break;
                case 'text':
                default:
                    $fieldType = TextType::class;
            }

            $builder->add($fieldName, $fieldType, [
                'label' => $label,
                'required' => false,
                'mapped' => false, // <-- important !
                'attr' => [
                    'placeholder' => $fieldOptions['placeholder'] ?? '',
                    'title' => $helper,
                ],
                'help' => $helper,
                'data' => $content[$fieldName] ?? null,
            ]);
        }

        $builder->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-primary mt-4',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuLink::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'data_editor_item',
        ]);
    }
}
