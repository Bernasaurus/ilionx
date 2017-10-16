<?php
namespace WeddingGuests\Entity;
use WeddingGuests\Classes\Day;
use WeddingGuests\Classes\GuestType;

class DayGuest extends \WeddingGuests\Entity\Guest {

    /**
     * @var Day
     */
    private $type;

    public function __construct(Day $type)
    {

        $this->type = $type;
    }

    /** @return Day */
    public function getType()
    {
        return $this->type;
    }
}