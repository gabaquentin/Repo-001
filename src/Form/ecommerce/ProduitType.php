<?php

namespace App\Form\ecommerce;

use App\Entity\CategorieProd;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\CategorieProdRepository;
use App\Repository\VilleRepository;
use App\Services\ecommerce\PackTools;
use App\Services\ecommerce\Tools;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ProduitType extends AbstractType
{
    private $repoCategorie;
    private $repoVille;
    private $tools;
    private $security;
    private $packTools;

    public function __construct(Security $security, CategorieProdRepository $repo,Tools $tools,VilleRepository $villeRepository,PackTools $packTools)
    {
        $this->repoCategorie = $repo;
        $this->tools = $tools;
        $this->repoVille = $villeRepository;
        $this->security = $security;
        $this->packTools = $packTools;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if($user->isAdmin())
            $sc = $this->repoCategorie->findSousCategories();
        else
            $sc = $this->repoCategorie->findSousCategoriesById($this->packTools->getEnabledCategories($user));

        $builder
            ->add('nom')
            ->add('categorieProd',EntityType::class,[
                "class"=>CategorieProd::class,
                "choice_label"=>"nomCategorie",
                "choices"=> $sc,
            ])
            ->add('typeTransaction',ChoiceType::class,[
                "choices"=>$this->tools->getTypeTransaction()
            ])
            ->add('prix')
            ->add('prixPromo')
            //->add('images')
            ->add('localisation')
            ->add('visiblite')
            ->add('dureeSejour')
            ->add('meuble')
            //->add('produitsAssocies')
            //->add('attributs')
            ->add('caracteristique',CaracteristiquesType::class)
            ->add('dimension',DimensionType::class)
            ->add('ville',EntityType::class,[
                'class'=> Ville::class,
                "choice_label"=>"villes",
                'empty_data'=>'aucune',
                'choices'=> $this->repoVille->findAll(),
            ])
            //->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
