<?php 

require_once("./Card.php");

class Deck
{
    private $cards = [];

    public function __construct()
    {
        $suits = ['schoppen', 'harten', 'klaveren', 'ruiten'];
        $values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'boer', 'vrouw', 'heer', 'aas'];
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
                $this->cards[] = new Card($suit, $value);
            }
        }
    }

    public function drawCard(): Card
    {
        if (!empty($this->cards)) {
            $index = array_rand($this->cards);
            $card = $this->cards[$index];
            unset($this->cards[$index]);
        } else {
            throw new Exception("All cards have been dealt, shuffle deck");
        }
        return $card;
    }
}