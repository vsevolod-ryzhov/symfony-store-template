<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Create;


use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, [
                'label' => 'Название'
            ])
            ->add('url', Type\TextType::class, [
                'label' => 'URL'
            ])
            ->add('sku', Type\TextType::class, [
                'label' => 'Артикул'
            ])
            ->add('price', Type\NumberType::class, [
                'label' => 'Цена'
            ])
            ->add('priceOld', Type\NumberType::class, [
                'label' => 'Старая цена ',
                'required' => false
            ])
            ->add('warehouse', Type\IntegerType::class, [
                'label' => 'Количество на складе'
            ])
            ->add('weight', Type\NumberType::class, [
                'label' => 'Вес'
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Описание',
                'required' => false
            ])
            ->add('metaName', Type\TextType::class, [
                'label' => 'Мета заголовок',
                'required' => false
            ])
            ->add('metaKeywords', Type\TextType::class, [
                'label' => 'Мета ключевые слова',
                'required' => false
            ])
            ->add('metaDescription', Type\TextType::class, [
                'label' => 'Мета описание',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }
}