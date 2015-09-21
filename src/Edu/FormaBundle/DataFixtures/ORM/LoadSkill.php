<?php
// src/Edu/FormaBundle/DataFixtures/ORM/LoadSkill.php

namespace Edu\FormaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edu\FormaBundle\Entity\Skill;

class LoadSkill implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Liste des noms de compétences à ajouter
        $names = array('Informatique', 'Cuisine', 'Business', 'Dessin', 'Photoshop', 'Blender', 'Office');

        foreach ($names as $name) {
            // On crée la compétence
            $skill = new Skill();
            $skill->setName($name);

            // On la persiste
            $manager->persist($skill);
        }

        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
    }
}