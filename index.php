<?php

require_once("./Dealer.php");
require_once("./Blackjack.php");
require_once("./Player.php");
require_once("./Deck.php");
require_once("./Card.php");
require_once("./Hand.php");

$dealer = new Dealer(new Blackjack(), new Deck(6));

while (true) {
    $autoPlay = readline("Do you want to play with friends or autoplay? (friends - auto) => .. ");

    if ($autoPlay == 'friends' || $autoPlay == 'auto') {
        $amountPlayers = getAmountPlayers();

        switch ($autoPlay) {
            case 'auto':
                setPlayersAutomatically($amountPlayers, $dealer);
                break;
            case 'friends':
                setPlayersManually($amountPlayers, $dealer);
                break;
        } 
        $dealer->addPlayers(new Player('Dealer', 0));
        echo PHP_EOL . "Let's Play!";
        $dealer->dealCards();
        $dealer->playGame();
    } else {
        echo 'Invalid input, please try again.' . PHP_EOL;
    }
}

function getAmountPlayers(): int
{
    $amount = readline('How many players would you like to play with? (2 - 12) .. ');

    if (!is_numeric($amount) || $amount < 2 || $amount > 12) {
        echo 'Amount of players invalid, please try again.' . PHP_EOL;
    } else {
        return $amount;
    }
}

function setPlayersAutomatically($amount, $dealer): void
{
    $name = ['Jennifer', 'Foivos', 'Cesco', 'Nando', 'Nique', 'Romano', 'Milo', 'Gaby', 'Eugene', 'Jose', 'Sarah',
    'Luuk', 'Kian', 'Stefan', 'Iris', 'KlangKuenstler', 'Kobosil', 'Oguz', 'Joris'];

    for ($x = 0; $x < $amount; $x++) {
        $dealer->addPlayers(new Player(array_pop($name), random_int(5, 500)));
    }
}

function setPlayersManually($amount, $dealer): void
{
    for ($x = 0; $x < $amount; $x++) { 
        $name = getName();
        $bet = getBet();
        $dealer->addPlayers(new Player($name, $bet));
    }
}

function getName(): string
{
    $name = readline("What's your name? => ..  ");

    if (!preg_match('/^[a-z]*$/i', $name)) {
        echo 'Invalid name, please try again.' . PHP_EOL;
    } else {
        return $name;
    }
}

function getBet(): string
{
    $bet = readline('Please enter your bet. ');

    if (!is_numeric($bet) || $bet < 5 || $bet > 500) {
        echo 'Invalid bet, please try again.' . PHP_EOL;
    } else {
        return $bet;
    }
}
























// private function playGame()
// {
//     foreach ($this->players as $player) {
//         foreach ($player->playingHands as $hand) {
//             $index = (count($player->hands) - 1);
//             $hand = $player->hands[$index];
//             $turn = ($index != 0) ? ": hand $index" : "'s turn";
//             echo $player->name . $turn;

//             for ($y = 0; $y < count($player->hands); $y++) { 
//                 $points = $this->BJ->points($hand);
//             }
//         }
//     }
// } 
