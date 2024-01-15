<?php

use function PHPSTORM_META\map;

class Card
{
    private $suit;
    private $value;

    public function __construct($suit, $value)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->validation($suit, $value);
    }

    private function validation($suit, $value): void
    {
        if ($suit != 'harten' && $suit != 'schoppen' && $suit != 'klaveren' && $suit != 'ruiten') {
            throw new Exception('Suit is invalid');
        } 
        if (is_numeric($value) && $value > 10 || $value < 2) {
            throw new Exception('Value is invalid');
        }
        if (!is_numeric($value) && $value != 'boer' && $value != 'vrouw' && $value != 'heer' && $value != 'aas') {
            throw new Exception('Value is invalid');
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

    public function getValue(): string
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
        return match ($this->value) {
            'boer', 'vrouw', 'heer' => 10,
            'aas' => 11,
            default => $this->value
        };
    }
}