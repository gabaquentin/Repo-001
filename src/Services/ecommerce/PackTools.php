<?php


namespace App\Services\ecommerce;


use App\Entity\BoostedProducts;
use App\Entity\CategorieProd;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\BoostedProductsRepository;
use App\Repository\CategorieProdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PackTools
{
    private $repCat;
    private $appKernel;
    private $repBoostedProd;
    private $em;

    public function __construct(CategorieProdRepository $repCat, KernelInterface $appKernel, BoostedProductsRepository $repBoostedProd, EntityManagerInterface $em)
    {
        $this->repCat = $repCat;
        $this->appKernel = $appKernel;
        $this->repBoostedProd = $repBoostedProd;
        $this->em = $em;
    }

    /**
     * @return BoostedProducts
     */
    private function getBoostedProductsEntity()
    {
        $data = $this->repBoostedProd->findAll();
        if (empty($data)) {
            $bProd = new BoostedProducts();
            $this->em->persist($bProd);
            $this->em->flush();
            return $bProd;
        }
        return $data[0];
    }

    /**
     * la structure de $data
     *  [
     *      [
     *          "idProd"=>"",
     *          "idCat"=>"",
     *          "duration"=>"" en jour
     *      ]
     * ]
     * @param array $data
     */
    public function persistBoostedProduct(array $data)
    {
        $bProd = $this->getBoostedProductsEntity();
        $boostedProducts = $bProd->getBoostedProducts();
        foreach ($data as $datum) {
            $idProd = $datum["idProd"];
            $duration = $datum["duration"];
            $idCat = $datum["idCat"];
            if (array_key_exists($idProd, $boostedProducts)) {
                $endDate = $boostedProducts[$idProd]["endDate"];
            } else {
                $endDate = new \DateTime();
            }
            try {
                $endDate = $endDate->add(new \DateInterval('P' . $duration . 'D'));
            } catch (\Exception $e) {
            }
            $boostedProducts[$idProd] = [
                "idCat" => $idCat,
                "endDate" => $endDate,
            ];
        }

        $bProd->setBoostedProducts($boostedProducts);
        $this->em->persist($bProd);
        $this->em->flush();
    }

    public function deleteBoostedProduct(Produit $produit)
    {
        $bProd = $this->getBoostedProductsEntity();
        $boostedProducts = $bProd->getBoostedProducts();
        if(array_key_exists($produit->getId(),$boostedProducts))
        {
            unset($boostedProducts[$produit->getId()]);
            $bProd->setBoostedProducts(array_values($boostedProducts[$produit->getId()]));
            $this->em->persist($bProd);
            $this->em->flush();
        }
    }

    /**
     * @param array|null $idCategories
     * @return Produit[]
     */
    public function getBoostedProducts(?array $idCategories)
    {
        $idProduits = [];
        $now = (new \DateTime());
        $bProd = $this->getBoostedProductsEntity();
        $boostedProducts = $bProd->getBoostedProducts();

        foreach ($boostedProducts as $idProd => $data) {
            if ($now >= $data["endDate"]) {
                unset($boostedProducts[$idProd]);
            } else {
                $idProduits[] = $idProd;
            }
        }
        if (!empty(array_diff(array_keys($bProd->getBoostedProducts()), array_keys($boostedProducts)))) {
            $bProd->setBoostedProducts($boostedProducts);
            $this->em->persist($bProd);
            $this->em->flush();
        }

        return $this->em->getRepository(Produit::class)->FindValidProducts((empty($idCategories) ? null : $idCategories), null, $idProduits);
    }

    public function showUserPackDetails(User $user)
    {
        $cats = [];
        $catsBoost = [];
        /** @var CategorieProd[] $categories */
        $categories = $this->repCat->findSousCategories();
        foreach ($categories as $category) {
            $cats["{$category->getId()}"] = $category->getNomCategorie();
        }
        $data = [
            "postes" => [
                "postes" => [],
                "blaz" => "",
                "titre" => "",
                "total" => 0,
            ],
            "boost" => [
                "boost" => [],
                "blaz" => "",
                "titre" => "",
                "total" => 0,
            ],
        ];
        $types = ["postes", "boost"];
        $packs = $user->getPackProduct();
        foreach ($packs as $pack) {
            foreach ($types as $type) {
                if (key_exists($type, $pack)) {
                    foreach ($pack[$type]["categories"] as $id => $values) {
                        $data[$type]["total"] += intval($values);
                        $nom = $cats["$id"];
                        $data[$type][$type]["$nom"] = intval($values);
                    }
                    $data[$type]["blaz"] = $pack["blaz"];
                    $data[$type]["titre"] = $pack["titre"];
                }
            }
        }
        return $data;
    }

    /**
     * diminue de un le nombre de poste d'une catégorie
     * @param User $user
     * @param int $idCategory
     * @param string $typePack
     * @return array
     */
    public function subtractUnitToPack(User $user, int $idCategory, $typePack = "postes")
    {
        $packs = $user->getPackProduct();
        foreach ($packs as $key => $pack) {
            if (array_key_exists($typePack, $pack)) {
                foreach ($pack[$typePack]["categories"] as $category => $nbrPost) {
                    if ($idCategory == $category) {
                        $pack[$typePack]["categories"][$category] -= 1;
                        $packs[$key] = $pack;
                        if ($pack[$typePack]["categories"][$category] < 0) {
                            unset($packs[$key]);
                            $packs = array_values($packs);
                        }
                        break;
                    }
                }
            }
        }
        return $packs;
    }

    /**
     * return les catégories qui sont prises en charge par le boost
     *
     * @param User $user
     * @param string $typePack
     * @return array le nouveau pack de l'utilisateur
     */
    public function getEnabledCategories(User $user, $typePack = "postes")
    {
        $cats = [];
        $packs = $user->getPackProduct();
        foreach ($packs as $pack) {
            if (array_key_exists($typePack, $pack)) {
                foreach ($pack[$typePack]["categories"] as $category => $nbrPost) {
                    if (intval($nbrPost) > 0)
                        $cats[] = intval($category);
                }
            }
        }

        return $cats;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canPost(User $user)
    {
        return intval($this->showUserPackDetails($user)["postes"]["total"]) > 0;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasBoost(User $user)
    {
        return intval($this->showUserPackDetails($user)["boost"]["total"]) > 0;
    }

    /**
     * génére les information de packs à enregistrer dans la base de donnée
     * @param array $data
     * @return array
     */
    public function generatePackData(array $data)
    {
        $packs = [
            "id" => uniqid(),
            "titre" => $data["titre"],
            "blaz" => $data["blaz"],
        ];

        if ($data["post"] == 0) {
            $packs["id"] = 2;
            $packs = array_merge($packs, $this->boostTemplate($data));
        } elseif ($data["duration"] == 0) {
            $packs["id"] = 1;
            $packs = array_merge($packs, $this->postTemplate($data));
        }

        return [$packs];
    }

    /**
     * @param User $user
     * @param array $packs
     * @return array
     * [
     *      ""=>[
     *              "postes|boost"=>[
     *                                   "categories"=>["idCat"=>"nbreJours|nbrePostes"]
     *                              ]
     *          ]
     * ]
     */
    public function mergeUserPacks(User $user, $packs)
    {
        $userPacks = $user->getPackProduct();
        foreach ($packs as $pack) {
            if ($pack["id"] == 1 || $pack["id"] == 2) {
                $exist = -1;
                foreach ($userPacks as $key => $userPack) {
                    if (($userPack["id"] == 1 || $userPack["id"] == 0) && $pack["id"] == 1) {
                        if ($userPack["id"] == 0) {
                            $save = $userPack;
                            $userPack = $pack;
                            $pack = $save;
                        }
                        foreach ($pack["postes"]["categories"] as $id => $nbrPostes) {
                            if (array_key_exists($id, $userPack["postes"]["categories"]))
                                $userPack["postes"]["categories"]["$id"] += $pack["postes"]["categories"]["$id"];
                            else
                                $userPack["postes"]["categories"]["$id"] = $pack["postes"]["categories"]["$id"];
                        }

                    } elseif ($userPack["id"] == 2) {
                        $exist = $key;
                    }

                    $userPacks[$key] = $userPack;
                }
                if($exist!=-1 && $pack["id"] == 2)
                {
                    $userPack = $userPacks[$exist];
                    foreach ($pack["boost"]["categories"] as $id => $nbrJours) {
                        if (array_key_exists($id, $userPack["boost"]["categories"]))
                            $userPack["boost"]["categories"]["$id"] += $pack["boost"]["categories"]["$id"];
                        else
                            $userPack["boost"]["categories"]["$id"] = $pack["boost"]["categories"]["$id"];
                    }
                    $userPacks[$exist] = $userPack;
                }
                else if ($exist==-1 && $pack["id"] == 2)
                    $userPacks = array_merge($userPacks, $packs);
            }
            else
                $userPacks = array_merge($userPacks, $packs);

        }
        return $userPacks;
    }

    /**
     * recupére les info de pack pour un nouveau utilisateur
     *
     * @return array
     */
    public function getDefaultPack()
    {
        $n = 5;
        $data = [];
        $cats = $this->repCat->findSousCategories();
        foreach ($cats as $cat) {
            $data["{$cat->getId()}"] = $n;
        }
        return [
            [
                "id" => "0",
                "titre" => "Default Pack",
                "description" => "Ce pack vous deonne la possibilité de poste 5 annonces gratuitement",
                "blaz" => "/frontend/img/illustrations/smile.svg",
                "prixBase" => "0 F CFA",
                "postes" => [
                    "nbrPostes" => $n,
                    "categories" => $data,
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    private function getPackInfoPath()
    {
        return $this->appKernel->getProjectDir() . "/src/Services/ecommerce/packinfo.json";
    }

    /**
     * recupère les info concernant les packs pour les afficher
     * @return array
     */
    function getPackInfoContent()
    {
        return json_decode(file_get_contents($this->getPackInfoPath()), true);
    }

    /**
     * ecrire les informations concernant les packs qui seront affichés dans le front
     *
     * @param array $data
     * @return mixed
     */
    function setPackInfoContent(array $data)
    {
        return file_put_contents($this->getPackInfoPath(), json_encode($data));
    }

    private function postTemplate(array $data)
    {
        return [
            "postes" => [
                "nbrPostes" => $data["post"],
                "categories" => $this->coupleCatWithValue($data["categories"], $data["post"]),
            ]
        ];
    }

    private function boostTemplate(array $data)
    {
        return [
            "boost" => [
                "duration" => $data["duration"],
                "categories" => $this->coupleCatWithValue($data["categories"], $data["duration"]),
            ]
        ];
    }

    private function coupleCatWithValue(array $categories, $value)
    {
        $res = [];
        foreach ($categories as $category) {
            $res["$category"] = $value;
        }
        return $res;
    }
}