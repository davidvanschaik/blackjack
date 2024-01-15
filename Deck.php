<?php

class Deck
{
    private $deck = [];

    public function __construct($decks = 2)
    {
        $suits = ['harten', 'schoppen', 'klaveren', 'ruiten'];
        $values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'boer', 'vrouw', 'heer', 'aas'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                for ($x = 0; $x < $decks; $x++) {
                    $this->deck[] = new Card($suit, $value);
                }
            }
        }
    }

    public function shuffleCheck(): array
    {
        return $this->deck;
    }

    public function drawCard(): Card
    {
        if (count($this->deck) > 1) {
            shuffle($this->deck);
            return array_pop($this->deck);
        } else {
            throw new Exception('Deck needs to be shuffled');
        }
    }
}