<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название услуги',
                'required' => true,
            ])
            ->add('cost', NumberType::class, [
                'label' => 'Стоимость',
                'required' => true,
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Длительность (час)',
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
            'data_class' => Service::class,
        ]);
    }
}
