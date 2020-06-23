<?php

declare(strict_types=1);


namespace App\Domain\User\UseCase\Create;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', Type\EmailType::class, [
                'label' => 'Email'
            ])
            ->add('phone', Type\TelType::class, [
                'label' => 'Телефон'
            ])
            ->add('surname', Type\TextType::class, [
                'label' => 'Фамилия'
            ])
            ->add('name', Type\TextType::class, [
                'label' => 'Имя'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }
}