<?php

namespace WeddingGuests\Repository;
use Doctrine\DBAL\Connection;
use WeddingGuests\Classes\Afternoon;
use WeddingGuests\Classes\Day;
use WeddingGuests\Classes\Evening;
use WeddingGuests\Entity\AfternoonGuest;
use WeddingGuests\Entity\DayGuest;
use WeddingGuests\Entity\EveningGuest;
use WeddingGuests\Entity\Guest;

/**
 * Created by PhpStorm.
 * User: b_ven
 * Date: 19-5-2017
 * Time: 21:10
 */
class GuestRepository
{

    /**
     * @var Connection
     */
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function save(Guest $guest)
    {
        $guestData = array(
            'id' => $guest->getId(),
            'firstName' => $guest->getFirstName(),
            'lastName' => $guest->getLastName(),
            'type' => $guest->getType()->getType()
        );

        if ($guest->getId()) {
            $this->db->update('guests', $guestData, array('id' => $guest->getId()));
        } else {
            $this->db->insert('guests', $guestData);
            // Get the id of the newly created guest and set it on the entity.
            $id = $this->db->lastInsertId();
            $guest->setId($id);
        }
    }

    /**
     * @param Guest $guest
     * @return int
     */
    public function delete(Guest $guest)
    {
        return $this->db->delete('guests', array('id' => $guest->getId()));
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->db->fetchColumn('SELECT COUNT(id) FROM guests');
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function find($id)
    {
        $guestData = $this->db->fetchAssoc('SELECT * FROM guests WHERE id = ?', array($id));
        return $guestData ? $this->buildGuest($guestData) : FALSE;
    }

    /**
     * @param string[] $orderBy
     * @return string[]
     */
    public function findAll($orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('lastName' => 'ASC');
        }
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('g.*')
            ->from('guests', 'g')
            ->orderBy('g.' . key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $guestsData = $statement->fetchAll();
        $guests = array();
        foreach ($guestsData as $guestData) {
            $guestId = $guestData['id'];
            $guests[$guestId] = $this->buildGuest($guestData);
        }
        return $guests;
    }

    /**
     * @param string[] $guestData
     * @return string[]
     */
    protected function buildGuest($guestData)
    {
        switch ($guestData['type']) {
            case 'Evening':
                $guest = new EveningGuest(new Evening());
                break;
            case 'Afternoon':
                $guest = new AfternoonGuest(new Afternoon());
                break;
            default:
                $guest = new DayGuest(new Day());
                break;
        }
        $guest->setId($guestData['id']);
        $guest->setFirstName($guestData['firstName']);
        $guest->setLastName($guestData['lastName']);
        return $guestData;
    }
}