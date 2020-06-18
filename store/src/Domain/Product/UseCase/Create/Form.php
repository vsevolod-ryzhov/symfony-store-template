<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Create;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', Type\TextType::class, [
                'label' => 'Название',
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('url', Type\TextType::class, [
                'label' => 'URL',
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('sku', Type\TextType::class, [
                'label' => 'Артикул',
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('price', Type\NumberType::class, [
                'label' => 'Цена',
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('priceOld', Type\NumberType::class, [
                'label' => 'Старая цена ',
                'required' => false,
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('warehouse', Type\IntegerType::class, [
                'label' => 'Количество на складе',
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('weight', Type\NumberType::class, [
                'label' => 'Вес',
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', Type\TextareaType::class, [
                'label' => 'Описание',
                'required' => false,
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('metaTitle', Type\TextType::class, [
                'label' => 'Мета заголовок',
                'required' => false,
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('metaKeywords', Type\TextType::class, [
                'label' => 'Мета ключевые слова',
                'required' => false,
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('metaDescription', Type\TextType::class, [
                'label' => 'Мета описание',
                'required' => false,
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }
}