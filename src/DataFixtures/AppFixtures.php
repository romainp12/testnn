<?php

namespace App\DataFixtures;

use App\Entity\Language;
use App\Entity\Personality;
use App\Entity\Theme;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $theme = new Theme();
        $theme->setName("Voyage");

        $theme1 = new Theme();
        $theme1->setName("Loisirs");

        $theme2 = new Theme();
        $theme2->setName("Musique");
        $theme2->setParent($theme1);

        $theme3 =  new Theme();
        $theme3->setName("Sport");
        $theme3->setParent($theme1);

        $personnality = new Personality();
        $personnality->setName("energique");
        $personnality1 = new Personality();
        $personnality1->setName("cultivé");

        $language = new Language();
        $language->setName("Français");

        $language1 = new Language();
        $language1->setName("Anglais");

        $language2 = new Language();
        $language2->setName("Espagnol");

        $language3 = new Language();
        $language3->setName("Chinois");

        $user = new User();
        $user->setEmail('blaise.pinheiro@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'hVnN27SC}p.94-$x'
        ));
        $birthday = "03-11-1988";
        $desired_length = 30; //or whatever length you want
        $unique = uniqid();

        $your_random_word = substr($unique, 0, $desired_length);
        $user->setIdSubscription($your_random_word);
        $user->setName("Peneau");
        $user->setGender(false);
        $user->setBirthdate(new \DateTime($birthday));
        $user->setRoles(array("ROLE_ADMIN"));
        $manager->persist($user);

        $manager->persist($theme);
        $manager->persist($theme1);
        $manager->persist($theme2);
        $manager->persist($theme3);

        $manager->persist($personnality);
        $manager->persist($personnality1);

        $manager->persist($language);
        $manager->persist($language1);
        $manager->persist($language2);
        $manager->persist($language3);

        $manager->flush();
    }
}
