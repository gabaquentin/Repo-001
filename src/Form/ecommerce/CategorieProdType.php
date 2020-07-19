<?php

namespace App\Form\ecommerce;

use App\Entity\CategorieProd;
use App\Repository\CategorieProdRepository;
use App\Services\ecommerce\Tools;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieProdType extends AbstractType
{
    private $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formOption = [
            'class'=>CategorieProd::class,
            'query_builder' => function (CategorieProdRepository $catRepository)  {
                return $catRepository->findAllCategoriesForm();
            },
            'choice_label' => function (CategorieProd $category) {
                return $category->getNomCategorie();
            }
        ];
        $builder
            ->setAction($options["action"])
            ->add('nomCategorie')
            ->add('typeCategorie',ChoiceType::class, [
                'choices'  =>$this->tools->getTypeProduit()
            ])
            ->add('categorieParent',EntityType::class, $formOption)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategorieProd::class,
        ]);
    }
}
