<?php

declare(strict_types=1);


namespace App\Domain\Category\Filter\CategoryIndex;


use App\Domain\Category\CategoryQuery;
use App\Domain\Category\Service\CategoryDecorator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    /**
     * @var CategoryQuery
     */
    private $query;

    public function __construct(CategoryQuery $query)
    {
        $this->query = $query;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'ID',
                'onchange' => 'this.form.submit()'
            ]])
            ->add('name', Type\TextType::class, ['required' => false, 'attr' => [
                'placeholder' => 'Название',
                'onchange' => 'this.form.submit()',
            ]])
            ->add('parent', Type\ChoiceType::class, [
                'required' => false,
                'choices' => ['' => ''] + array_flip(CategoryDecorator::listPrettyPrint($this->query->allTree())),
                'attr' => [
                    'placeholder' => 'Родитель',
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