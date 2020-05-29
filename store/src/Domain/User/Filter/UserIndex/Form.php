<?php

declare(strict_types=1);


namespace App\Domain\User\Filter\UserIndex;


use App\Domain\User\Helper\RoleHelper;
use App\Domain\User\Helper\UserHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Email',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('phone', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Телефон',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('surname', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Фамилия',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('name', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Имя',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('status', Type\ChoiceType::class,
                [
                    'choices' => array_flip(UserHelper::statusList()),
                    'required' => false,
                    'placeholder' => 'Доступные статусы',
                    'attr' => ['onchange' => 'this.form.submit()']
                ])
            ->add('role', Type\ChoiceType::class,
                [
                    'choices' => array_flip(RoleHelper::rolesList()),
                    'required' => false,
                    'placeholder' => 'Доступные поли',
                    'attr' => ['onchange' => 'this.form.submit()']
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}