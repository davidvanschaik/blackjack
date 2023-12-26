<?php

require_once("./Blackjack.php");
require_once("./Player.php");
require_once("./Deck.php");

class Dealer
{
    private Blackjack $blackjack;
    private Deck $deck;
    private array $players = [];

    public function __construct(Blackjack $blackjack, Deck $deck)
    {
        $this->blackjack = $blackjack;
        $this->deck = $deck;
    }

    public function addPlayer(Player $player)
    {
        $this->players[] = $player;
    }

    private function inTheGame()
    {
        return array_filter($this->players, fn ($player) => $player->stillPlaying == true);
    }

    private function msg($hand)
    {
        return (string)$this->blackjack->scoreHand($hand);
    }

    private function points($hand)
    {
        return $this->blackjack->points($hand);
    }

    public function playGame()
    {
        foreach ($this->players as $person) {
            for ($x = 0; $x < 2; $x++) {
                $person->addCard($this->deck->drawCard());
            }
        }

        $playGame = true;
        while ($playGame) {
            if (empty($this->inTheGame())) {
                $playGame = false;
                $this->results();
                break;
            }
            foreach ($this->inTheGame() as $person) {
                if ($person->name() == 'Dealer') {
                    $this->dealer($person);
                } else {
                    $this->player($person);
                }
            }
        }
    }

    private function dealer(Player $person)
    {
        echo PHP_EOL . $person->name() . "'s turn. ";

        while($person->stillPlaying) {
            if ($this->points($person->hand()) == 21 && count($person->hand()) == 2) {
                echo $person->showHand()  . '=> ' . $this->msg($person->hand()) . PHP_EOL;
                $person->stillPlaying = false;
            } elseif ($this->points($person->hand()) >= 18) {
                echo $person->showHand()  . '=> ' . $this->msg($person->hand()) . ' Dealer stops' . PHP_EOL;
                $person->stillPlaying = false;
            } elseif ($this->points($person->hand()) < 18 && count($person->hand()) == 2) {
                echo $person->showHand() . '=> ' . $this->msg($person->hand()). PHP_EOL;
                $person->addCard($card = $this->deck->drawCard());
                echo $person->name() . ' drew ' . $card->show() . ' => ' . $this->msg($person->hand()) . PHP_EOL;
            } elseif ($this->points($person->hand()) < 18) {
                $person->addCard($card = $this->deck->drawCard());
                echo $person->name() . ' drew ' . $card->show() . ' => ' . $this->msg($person->hand()) . PHP_EOL;
                $person->stillPlaying = false;
            }
        }
    }

    private function player(Player $person)
    {
        echo PHP_EOL . $person->name() . "'s turn. ";

        if ($this->points($person->hand()) == 21) {
            echo $person->showHand() . '=> ' . $this->msg($person->hand()) . PHP_EOL;
            $person->stillPlaying = false;
            } elseif ($this->points($person->hand()) < 21) {
                echo $person->showHand() . '=> ' . $this->points($person->hand()) . PHP_EOL;
                while($person->stillPlaying) {
                $choice =  strtolower(readline('Hit (H) or Stand (S) ...? '));
                if ($choice == 's') {
                    $person->stillPlaying = false;
                    echo $person->name() . ' stops ' . PHP_EOL;
                } else {
                    $person->addCard($card = $this->deck->drawCard());
                    echo 'You got: ' . $card->show() . ' => ' . substr($person->showHand(), 10) . '=> ' . $this->msg($person->hand()) . PHP_EOL;
                    if ($this->points($person->hand()) >= 21) {
                        $person->stillPlaying = false;
                    } elseif ($this->points($person->hand()) < 21 && count($person->hand()) > 4) {
                        $person->stillPlaying = false;
                    }
                }
            }
        }
    }

    private function results()
    {
        $dealer = $this->players[count($this->players) - 1];
        unset($this->players[count($this->players) - 1]);
        $this->blackjack->resultValidation($dealer, $this->players);
    }
}