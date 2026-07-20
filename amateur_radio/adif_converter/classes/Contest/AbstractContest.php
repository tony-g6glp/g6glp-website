<?php

abstract class AbstractContest
{

    abstract public function getId();


    abstract public function getName();


    abstract public function validate(array $qsos);


    abstract public function buildQso(array $qso);


    abstract public function buildHeader(array $station);


}