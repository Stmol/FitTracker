<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 25.02.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FT\ExerciseBundle\Entity\Exercise;

class LoadExercisesData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $data = $this->getData();

        foreach ($data as $exercise) {
            $newExercise = new Exercise();
            $newExercise->setTitle($exercise['title']);

            $manager->persist($newExercise);
        }

        $manager->flush();
    }

    /**
     * Get mock data
     *
     * @return array
     */
    protected function getData()
    {
        return [
            ['title' => 'Жим штанги лёжа горизонтально'],
            ['title' => 'Отжимания от брусьев на трицепс'],
            ['title' => 'Выпады со штангой'],
            ['title' => 'Жим ногами в тренажёре'],
            ['title' => 'Французский жим со штангой лежа'],
            ['title' => 'Гиперэкстензия'],
            ['title' => 'Тяга горизонтального блока'],
            ['title' => 'Скручивания на наклонной скамье'],
        ];
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }
}