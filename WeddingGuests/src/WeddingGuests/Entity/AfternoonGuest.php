<?php
namespace WeddingGuests\Entity;
use WeddingGuests\Classes\Afternoon;
class AfternoonGuest extends \WeddingGuests\Entity\Guest {

    /**
     * @var Afternoon
     */
    private $type;

    public function __construct(Afternoon $type)
    {

        $this->type = $type;
    }

    /** @return Afternoon */
    public function getType()
    {
        return $this->type;
    }
}