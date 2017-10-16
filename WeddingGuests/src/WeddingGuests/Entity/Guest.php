<?php
/**
 * Created by PhpStorm.
 * User: b_ven
 * Date: 19-5-2017
 * Time: 22:09
 */

namespace WeddingGuests\Entity;


use WeddingGuests\Classes\GuestType;

abstract class Guest
{
    /** @var  int */
    protected $id;
    /** @var  string */
    protected $firstName;
    /** @var  string */
    protected $lastName;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /** @return GuestType */
    abstract public function getType();
}