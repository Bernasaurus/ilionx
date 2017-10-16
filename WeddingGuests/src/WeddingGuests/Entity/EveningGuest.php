<?php
namespace WeddingGuests\Entity;
use WeddingGuests\Classes\Evening;
class EveningGuest extends \WeddingGuests\Entity\Guest {

    /**
     * @var Evening
     */
    private $type;

    public function __construct(Evening $type)
    {

        $this->type = $type;
    }

    /** @return Evening */
    public function getType()
    {
        return $this->type;
    }
}