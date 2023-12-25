<?php

require_once("./Dealer.php");
require_once("./Blackjack.php");
require_once("./Player.php");
require_once("./Deck.php");
require_once("./Card.php");

$amount = readline("How many players would you like to play with ... ? ");

if (is_numeric($amount) && $amount > 1 && $amount < 12) {
    $dealer = new Dealer(new Blackjack(), new Deck());
    for ($x = 0; $x < $amount ; $x++) { 
        $name = readline("What's your name? ... ");
        $bet = readline($name . ', please place your bet: ');
        $dealer->addPlayer(new Player($name, $bet));
    }
    $dealer->addPlayer(new Player('Dealer', 0));
    echo PHP_EOL . "Let's Play!" . PHP_EOL;
    $dealer->playGame();
} else {
    echo "Invalid amount of players, try again" . PHP_EOL;
    die;
}
