<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 02.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\Manager;

interface EntityManagerInterface
{
    public function create();

    public function save($entity, $flush = true);

    public function delete($entity);

    public function getOneById($id);

    public function getAllLimited($limit, $offset);
}
