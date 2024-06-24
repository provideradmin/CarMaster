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
                'label' => 'Type',
                'required' => true,
            ])
            ->add('brand', null, [
                'label' => 'Brand',
                'required' => true,
            ])
            ->add('model', null, [
                'label' => 'Model',
                'required' => true,
            ])
            ->add('year', null, [
                'label' => 'Year',
                'required' => true,
            ])
            ->add('number', null, [
                'label' => 'Number',
                'required' => true,
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a client',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
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
