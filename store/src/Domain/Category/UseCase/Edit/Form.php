<?php

declare(strict_types=1);


namespace App\Domain\Category\UseCase\Edit;


use App\Domain\Category\CategoryQuery;
use App\Domain\Category\Service\CategoryDecorator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category_list = $this->query->allTree();
        foreach ($category_list as $key => $item) {
            if ($item['id'] === $options['current_category_id']) {
                unset($category_list[$key]);
            }
        }

        $builder
            ->add('name', Type\TextType::class, [
                'label' => 'Название'
            ])
            ->add('parent', Type\ChoiceType::class, [
                'label' => 'Родительская категория',
                'choices' => array_flip(CategoryDecorator::listPrettyPrint($category_list))
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
            'current_category_id' => null
        ]);
    }
}