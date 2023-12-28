<?php 

require_once("./Dealer.php");
require_once("./Blackjack.php");
require_once("./Player.php");
require_once("./Deck.php");
require_once("./Card.php");

$amount = readline("Please enter how many players you would like to play with, 2 - 12 => .. ");

$name = [
    'Jennifer', 'Foivos', 'Cesco', 'Nando', 'Nique', 'Romano', 'Kian', 'Stefan',
    'Noah', 'Thijs', 'Merlijn', 'Iris', 'Sarah', 'Eugene', 'Jose', 'Smokey',
    'Lizzy', 'Lola', 'Lex', 'Xander', 'Jordy', 'George', 'Teun', 'Bas', 'Chico',
    'Quinten', 'Koen', 'Damian'
];

if (is_numeric($amount) && $amount > 2 && $amount <= 12) {
    $dealer = new Dealer(new Blackjack(), new Deck());

    for ($x = 0; $x < $amount; $x++) { 
        $dealer->addPlayer(new Player(array_pop($name), random_int(0, 500)));
    }

    $dealer->addPlayer(new Player('Dealer', 0));
    echo PHP_EOL . "Let's Play!" . PHP_EOL;
    $dealer->playGame();
}