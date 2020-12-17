<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <= 15; $i++) {
            $article = new Article();
            $article->setTitle("Titre de l'article n° $i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("http://via.placeholder.com/640x360")
                    ->setCreatedAt(new \Datetime());
            $manager->persist($article);
        }
        $manager->flush();
    }
}
