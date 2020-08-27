<?php

namespace App\ValueObjects;

use Exception;

class PromotionConstructor
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int|null
     */
    private $promotionId;

    /**
     * @var int|null
     */
    private $giftId;

    /**
     * @var array
     */
    private $seats;

    /**
     * PromotionConstructor constructor.
     * @param array $constructorData
     * @throws Exception
     */
    public function __construct(array $constructorData)
    {
        if (empty($constructorData)) {
            throw new Exception("Constructor data can not be empty");
        }

        if (!key_exists('id', $constructorData) || !is_int($constructorData['id'])) {
            throw new Exception("Constructor id must set and type of integer");
        }

        if (
            !key_exists('promotion_id', $constructorData)
            || (
                $constructorData['promotion_id'] !== null
                && !is_int($constructorData['promotion_id'])
            )
        ) {
            throw new Exception("Constructor promotion id must set and type of integer");
        }

        if (
            !key_exists('gift_id', $constructorData)
            || (
                $constructorData['gift_id'] !== null
                && !is_int($constructorData['gift_id'])
            )
        ) {
            throw new Exception("Gift id must set and type of integer");
        }

        $this->id = $constructorData['id'];
        $this->promotionId  = $constructorData['promotion_id'];
        $this->giftId = $constructorData['gift_id'];
    }

    /**
     * TODO подумать над валидацией и отдельного объекта Seats
     *
     * @param array $seats
     */
    public function setSeats(array $seats)
    {
        $this->seats = $seats;
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function takeEmptySeat(): array
    {
        if (null === $this->seats) {
            throw new Exception("Seats are not set");
        }

        $seatTaken = false;
        foreach ($this->seats as &$seat) {
            if ($seat['id'] === $this->id) {
                $seat['promotion_id'] = $this->promotionId;
                $seat['gift_id'] = $this->giftId;
                $seatTaken = true;
                break;
            }
        }

        if (!$seatTaken) {
            array_push($this->seats, [
                'id' => $this->id,
                'promotion_id' => $this->promotionId,
                'gift_id' => $this->giftId,
            ]);
        }

        return array_unique($this->seats, SORT_REGULAR);
    }

    /**
     * @param int $constructorId
     * @param array $constructors
     * @return array
     */
    public static function remove(int $constructorId, array $constructors): array
    {
        foreach ($constructors as $key => $constructor) {
            if ($constructor['id'] === $constructorId) {
                unset($constructors[$key]);
            }
        }

        return $constructors;
    }
}
