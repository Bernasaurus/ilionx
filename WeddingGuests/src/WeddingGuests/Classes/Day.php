<?php
namespace WeddingGuests\Classes;
/**
 * Created by PhpStorm.
 * User: b_ven
 * Date: 16-10-2017
 * Time: 11:51
 */
class Day implements GuestType
{

    /** @return string */
    public function getType()
    {
        return 'Day';
    }
}