<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Car;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', null, [
                'label' => 'Тип авто',
                'required' => true,
            ])
            ->add('brand', null, [
                'label' => 'Марка',
                'required' => true,
            ])
            ->add('model', null, [
                'label' => 'Модель',
                'required' => true,
            ])
            ->add('year', null, [
                'label' => 'Год',
                'required' => true,
            ])
            ->add('number', null, [
                'label' => 'Номер',
                'required' => true,
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'name',
                'placeholder' => 'Укажите владельца',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить авто',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
