<?php

require_once("./Dealer.php");
require_once("./Blackjack.php");
require_once("./Player.php");
require_once("./Deck.php");
require_once("./Card.php");

$amount = readline("Please enter how many players you would like to play with, 2 - 12 => .. ");

if (!is_numeric($amount) || $amount < 2 && $amount > 12) {
    echo "Invalid amount of players, try again" . PHP_EOL;
    die;
} else {
    $dealer = new Dealer(new Blackjack(), new Deck());
    for ($x = 0; $x < $amount ; $x++) { 
        while(true) {
            $name = readline("What's your name? => ..  ");

            if (!preg_match('/^[a-z]*$/i', $name) || empty($name)) {
                echo 'Invalid name, try again. ' . PHP_EOL;
            } else {
                $bet = readline($name . ', please place your bet, 5 - 500: ');

                if (!is_numeric($bet) || $bet < 5 || $bet > 500 || empty($bet)) {
                    echo 'Invalid bet, try again.' . PHP_EOL;
                } else {
                    break;
                }
            }
        }
        $dealer->addPlayer(new Player($name, $bet));
    }
    $dealer->addPlayer(new Player('Dealer', 0));
    echo PHP_EOL . "Let's Play!" . PHP_EOL;
    $dealer->playGame();
}