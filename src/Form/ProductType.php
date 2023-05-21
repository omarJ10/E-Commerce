<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class, [
            'attr' => [
                'class' => 'form-control mt- mb-4',
            ]
        ])

        ->add('price', MoneyType::class,[
            'attr' => [
                'class' => 'form-control mt-2 mb-4',
        ]])
        ->add('anciente',TextType::class, [
            'attr' => [
                'class' => 'form-control mt-2 mb-4',
            ]
        ])
        ->add('rate',TextType::class, [
            'attr' => [
                'class' => 'form-control mt-2 mb-4',
            ]
        ])
        ->add('photo', FileType::class,
            [
                'data_class' => null,
                'attr' => ['class' => 'form-control mt-2'],
                'constraints'=> new File( ['mimeTypes'=>['image/jpeg','image/jpg','image/png','image/svg']],
            )])
        ->add('OK', SubmitType::class,
            ['attr' => [
                'class' => 'btn btn-primary mt-4 mb-2'
            ]])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
