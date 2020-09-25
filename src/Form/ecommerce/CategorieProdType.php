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
    private $repo;
    public function __construct(Tools $tools,CategorieProdRepository $catRepository)
    {
        $this->tools = $tools;
        $this->repo = $catRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formOption = [
            'class'=>CategorieProd::class,
            'choice_label' => function (CategorieProd $category) {
                return $category->getNomCategorie();
            },
            'required'=> false,
            'choices' => $this->repo->findAllCategories(),
        ];
        $builder
            ->setAction($options["action"])
            ->add('nomCategorie')
            ->add('typeCategorie',ChoiceType::class, [
                'choices'  =>$this->tools->getTypeProduit(),
            ])
            ->add('categorieParent',EntityType::class,$formOption)
            ->add('uniteAnnonce')
            ->add('uniteBoost')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategorieProd::class,
        ]);
    }
}
