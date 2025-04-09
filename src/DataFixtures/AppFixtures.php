<?php

namespace App\DataFixtures;

use App\Entity\Criteria;
use App\Entity\Filter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Filter 1
        $filter1 = new Filter();
        $filter1->setName('Product Filter');
        $filter1->setSelection('common');

        $criteria1a = new Criteria();
        $criteria1a->setType('amount');
        $criteria1a->setComparator('>');
        $criteria1a->setValue('50');
        $filter1->addCriteria($criteria1a);

        $criteria1b = new Criteria();
        $criteria1b->setType('title');
        $criteria1b->setComparator('starts_with');
        $criteria1b->setValue('Samsung');
        $filter1->addCriteria($criteria1b);

        $manager->persist($filter1);


        // Filter 2
        $filter2 = new Filter();
        $filter2->setName('Customer Filter');
        $filter2->setSelection('special');

        $criteria2a = new Criteria();
        $criteria2a->setType('date');
        $criteria2a->setComparator('to');
        $criteria2a->setValue('2022-01-01');
        $filter2->addCriteria($criteria2a);

        $criteria2b = new Criteria();
        $criteria2b->setType('amount');
        $criteria2b->setComparator('>');
        $criteria2b->setValue('1000');
        $filter2->addCriteria($criteria2b);

        $manager->persist($filter2);

        $manager->flush();
    }
}