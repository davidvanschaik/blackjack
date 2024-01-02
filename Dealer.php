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

    public function dealCards()
    {
        for ($x = 0; $x < 2; $x++) {
            foreach ($this->players as $person) {
                $person->addCard($this->deck->drawCard());
            }
        }
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

    private function dealerFirstCard()
    {
        foreach ($this->players as $player) {
            if ($player->name() == 'Dealer') {
                return $player->showHand('dealer');
            }
        }
    }

    public function playGame()
    {
        $playGame = true;
        while ($playGame) {
            if (empty($this->inTheGame())) {
                $playGame = false;
                $this->results();
                exit;
            }
            foreach ($this->inTheGame() as $person) {
                if ($person->name() == 'Dealer') {
                    echo PHP_EOL . $person->name() . "'s turn. ";
                    echo $person->showHand('result') . '=> ' . $this->msg($person->hand()) . PHP_EOL;
                    $this->dealer($person);
                } else {
                    echo PHP_EOL . $this->dealerFirstCard() . PHP_EOL;
                    echo PHP_EOL . $person->name() . "'s turn. ";
                    echo $person->showHand('result') . '=> ' . $this->msg($person->hand()) . PHP_EOL;
                    $this->player($person);
                }
            }
        }
    }

    private function dealer(Player $person)
    {
        while ($person->stillPlaying) {
        $points = $this->points($person->hand());

            if ($points >= 18 || $points < 21 && count($person->hand()) > 4) {
                $this->dealerStopsPlaying($person, $points);
            } elseif ($points < 18) {
                $this->dealerStillPlaying($person);
            } 
        }
        $this->playGame();
    }

    private function player(Player $person)
    {
        while ($person->stillPlaying) {
        $points = $this->points($person->hand());
            if (count($person->hand()) == 2 && $points < 21) {
                $this->openingsHand($person, $points);
            } elseif ($points >= 21 || $points < 21 && count($person->hand()) > 4) {
                $this->playerStopsPlaying($person, $points);
            } elseif ($points < 21) {
                $this->makeChoice($person, $points);
            } 
        }
        $this->playGame();
    }

    private function dealerStopsPlaying($person, $points)
    {
        if ($points > 21 || ($points < 21 && count($person->hand()) > 4)) {
            $person->stillPlaying = false;
        } elseif ($points >= 18 || $points <= 21) {
            echo $person->showHand('result')  . '=> ' . $this->msg($person->hand()) .  ' Dealer stops' . PHP_EOL;
            $person->stillPlaying = false;
        }
        $this->dealer($person);
    }

    private function dealerStillPlaying($person)
    {
        sleep(1);
        $person->addCard($card = $this->deck->drawCard());
        echo $person->name() . ' drew ' . $card->show() . ' =>' . $person->showHandRaw() . '=> ' . $this->msg($person->hand()) . PHP_EOL;
        $this->dealer($person);
    }

    private function openingsHand($person)
    {
        while (true) {
            if ($this->blackjack->splitCheck($person->hand()) == true) {
                $choice = strtolower(readline('Hit (H), Double Down (D), Split (SP) or Stand (S) ...? '));
            } else {
                $choice = strtolower(readline('Hit (H), Double Down (D) or Stand (S) ...? '));
            }
            if (!str_contains('h d s sp', $choice)) {
                echo 'Input does not match any action, try again.' . PHP_EOL;
            } else {
                break;
            }
        }
        if ($choice == 'sp') {
            $this->split($person);
        } elseif ($choice == 'd') {
            $this->doubleDown($person);
        } elseif ($choice == 's') {
            $this->playerStopsPlaying($person, $choice);
        } else {
            $this->playerStillPlaying($person);
        }
    }

    private function makeChoice($person)
    {
        while (true) {
            $choice = strtolower(readline('Hit (H) or Stand (S) ...? '));
            if (!str_contains('h s', $choice)) {
                echo 'Input does not match any action, try again.' . PHP_EOL;
            } else {
                break;
            }
        }

        if ($choice == 's') {
            $this->playerStopsPlaying($person, $choice);
        } else {
            $this->playerStillPlaying($person, $choice);
        }
    }

    private function playerStillPlaying($person)
    {
        $person->addCard($card = $this->deck->drawCard());
        echo 'You got: ' . $card->show() . ' => ' . $person->showHandRaw() . '=> ' . $this->msg($person->hand()) . PHP_EOL;
        $this->player($person);
    } 

    private function playerStopsPlaying($person, $choice)
    {
        if ($choice != 's') {
            $person->stillPlaying = false;
            $this->player($person);
        } else {
            echo $person->name() . ' stands ' . PHP_EOL;
            $person->stillPlaying = false;
            $this->player($person);
        }
    }

    private function doubleDown($person)
    {
        $person->addCard($this->deck->drawCard());
        $person->doubleDown();
        echo $person->name() . ' Doubled Down' . PHP_EOL;
        $this->player($person);
    }

    private function split($person)
    {
        $person->splitHand();
    }

    private function results()
    {
        foreach ($this->players as $key => $player) {
            if ($player->name() == 'Dealer') {
                $dealer = $player;
                unset($this->players[$key]);
                $this->blackjack->resultValidation($dealer, $this->players);
                break;
            }
        }
    }
}