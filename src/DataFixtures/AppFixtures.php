<?php

namespace App\DataFixtures;
use App\Entity\Produits;
use App\Entity\User;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("orel@user.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);

        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("orel@admin.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);



        $listCateg = [];
        for($i=0; $i <5; $i++)
        {
            $categorie = new Categorie();
            $categorie ->setNomCateg('Cakes' .$i);
            $manager->persist($categorie);
            $listCateg[]=$categorie;
        }

        $manager->flush();

        for($i=0; $i <5; $i++)
        {
            $produit = new Produits;
            $produit ->setNom('BÛCHE MARRONS POIRES' .$i);
            $produit->setPrix(50 .$i);
            $produit->setDescription('il est parfois difficile de réaliser des recettes aux marrons qui restent équilibrées en terme de sucre.' .$i);
            $produit->setImage('https://empreintesucree.fr/wp-content/uploads/2022/12/1-buche-marrons-poires-recette-patisserie-empreinte-sucree.jpg.webp' .$i);
            $produit->setIdCateg($listCateg[array_rand($listCateg)]);
            $manager->persist($produit);
        }

        $manager->flush();





    }
}
