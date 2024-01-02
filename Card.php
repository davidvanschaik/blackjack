<?php 

class Card
{
    private $suit;
    private $value;

    public function __construct($suit, $value)
    {
        $this->validate($suit, $value);
        $this->suit = $suit;
        $this->value = $value;
    }

    private function validate($suit, $value)
    {
        if ($suit != 'schoppen' && $suit != 'harten' && $suit != 'ruiten' && $suit != 'klaveren') {
            throw new Exception('Suit must be schoppen, ruiten, harten or klaveren');
        }
        if ((int)$value == $value && (int)$value < 2 && (int)$value > 10) {
            throw new Exception('Value must be a number between 2 or 10');
        }
        if ((int)$value != $value && $value != 'boer' && $value != 'vrouw' && $value != 'heer' && $value != 'aas') {
            throw new Exception('Value must be boer, vrouw, heer or aas');
        }
    }

    public function show(): string
    {
        $value = $this->getValue();

        $suit = match ($this->suit) {
            'harten' => '♥',
            'klaveren' => '♣',
            'schoppen' => '♠',
            'ruiten' => '♦'
        };

        return $suit . $value;
    }

    public function getValue()
    {
        $value = match ($this->value) {
            'boer' => 'B',
            'vrouw' => 'V',
            'heer' => 'H',
            'aas' => 'A',
            default => $this->value
        };

        return $value;
    }

    public function score(): int
    {
        $score = 0;

        if ($this->value == 'boer' || $this->value == 'vrouw' || $this->value == 'heer') {
            $score = 10;
        } elseif ($this->value == 'aas') {
            $score = 11;
        } else {
            $score = $this->value;
        }
        return $score;
    }
}