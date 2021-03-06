<?php

namespace SONUser\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


use SONUser\Entity\User;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setNome("David")
                ->setEmail("david.abraao.petro@gmail.com")
                ->setPassword(123456)
                ->setActive(true);


        $manager->persist($user);

        $user = new User();
        $user->setNome("Admin")
                ->setEmail("david.abraao.petro@gmail.com")
                ->setPassword(123456)
                ->setActive(true);

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder() {
        return 4;
    }
}
