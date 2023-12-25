<?php

require_once("./Dealer.php");
require_once("./Blackjack.php");
require_once("./Player.php");
require_once("./Deck.php");
require_once("./Card.php");

$name = [
    "Jennifer", "Eugene", "Jose", "Sarah", "Luuk",
    "Smokey", "Lola", "Lizzy", "Milo", "Gaby", "Shin",
    "Foivos", "Nando", "Cesco", "Nique", "Romano",
    "Noah", "Thijs", "Lex", "Nino", "Stefan", "Iris",
    "Geurt", "Daniel", "Jonah", "KlangKuenstler", "Kobosil",
    "Raxeller", "George", "Iskander", "Kian", "Spike",
    "Teun", "Jordy", "Chico"
];

$x = readline("How many players would you like to play with ... ? ");

if (is_numeric($x) && $x > 1 && $x < 12) {
    $dealer = new Dealer(new Blackjack(), new Deck());
    foreach (array_rand($name, $x) as $player) {
        $dealer->addPlayer(new Player($name[$player]));
    }
    $dealer->playGame();
} else {
    echo "Invalid amount of players, try again" . PHP_EOL;
    die;
}


