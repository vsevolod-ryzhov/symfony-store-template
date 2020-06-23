<?php

declare(strict_types=1);


namespace App\Domain\Product\UseCase\Photos\Upload;


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
            ->add('files', Type\FileType::class, [
                'label' => 'Фотография',
                'required' => false,
                'multiple' => true,
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control-file']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class
        ]);
    }
}