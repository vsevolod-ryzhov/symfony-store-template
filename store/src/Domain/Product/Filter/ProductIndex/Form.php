<?php

declare(strict_types=1);


namespace App\Domain\Product\Filter\ProductIndex;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'ID',
                'onchange' => 'this.form.submit()'
            ]])
            ->add('created_date', Type\DateType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'input' => 'string',
                    'attr' => [
                        'placeholder' => 'Дата создания',
                        'onchange' => 'this.form.submit()'
                    ]
                ])
            ->add('updated_date', Type\DateType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'input' => 'string',
                    'attr' => [
                        'placeholder' => 'Дата обновления',
                        'onchange' => 'this.form.submit()'
                    ]
                ])
            ->add('title', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Название',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('url', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'URL',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('sku', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Артикул',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('price_price', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Цена',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('warehouse', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Количество на складе',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('is_deleted', Type\ChoiceType::class, [
                'required' => false,
                'choices' => ['Нет' => 0, 'Да' => 1],
                'attr' => [
                    'placeholder' => 'Товар удален?',
                    'onchange' => 'this.form.submit()',
                ]
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