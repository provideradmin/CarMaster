<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Part;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название запчасти',
                'required' => true,
            ])
            ->add('cost', NumberType::class, [
                'label' => 'Стоимость',
                'required' => true,
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Количество',
                'required' => true,
            ])
            ->add('sellingPrice', NumberType::class, [
                'label' => 'Цена продажи',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Part::class,
        ]);
    }
}
