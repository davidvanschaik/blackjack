# BlackJack => 21!

## introduction
This repository contains a sumilation of a Blackjack game using PHP and Object-Oriented Programming (OOP). The project contains multiple PHP files, each file responsible for specific functionality.

## Project Structure
- **Deck.php**: Generates deck. returns a card to Card.php with the suit and value variables.
- **Card.php**: Contains the validation of the cards and the points.
- **Player.php**: Contains info like name, if still playing, bets and cards.
- **Dealer.php**: Keeps track who is still playing or is busted, also checks if dealer will hit or stand. 
- **Blackjack.php**: Contains all the result and bet calculations. 
- **index.php**: Asks user amount of players. Each player enters their name and bet.

## Features
- **Player Interaction**: Interacts with players. Players can Hit, Double Down, Split or Stand.
- **Deck**: The cards will be generated within Deck. Every card will be removed from Deck when is dealt. Remember a deck has 52 cards.
- **Dealer**: If dealer score < 18. Draw card else dealer stand. Player > 21 busted.
- **Scoring Calculations**: Calculates who won, the dealer or the players and how much they have won. 
- **Win or Loss**: The results will be shown after all players stand or busted.

## Setup 
To play this Blackjack game locally:

1. **Clone Repository**:
```bash
git clone https://github.com/davidvanschaik/blackjack.git
```

2. **Navigate project Directory**:
```bash
cd path/to/blackjack
```

3. **Run index.php and decide with how many players you are would like to play with**:
**Note**: Make sure you have PHP installed.
```bash
php index.php
How many players would you like to play with ... ?
```
After you have decided with how many players you would like to play, each player need to fill in their name and how much they bet.
**Note:** Use only letters for your name! 
```bash
What's your name? => .. yourName
yourName, please place your bet, 5 - 500: 200
```
After all players have given their name and bet the game will begin.

4. **Play game**:
Each player will be asked to Hit or Stand if points <= 21. At the end the results will appear. Winners, tied with dealer or losers with the bets.

## Game Rules

BlackJack is a bet game where each player needs to place a bet. The game is very simple. The person who is closest or equal to 21 have won, IF the dealer has lower than you. Players will be ask to hit or stand. At the opening hand, the player will be able to double down, this option doubles your bet but you can just hit once. If the values of the cards are equal to each other, also within the opening hand, the player can chose to split their cards, also than the bet will be doubled, because the cards will be devided in 2 hands. From there the rules are just the same. A person can chose to hit a card as many times the cards will below 21. If you have 5 cards and still below 21, that's 'Five Card Charlie', you won.

## Betting system
**How the bets for the winners will be calculated**:

- BlackJack means you have a Ace and a 10, BlackJack can only be hit in the openings hand. You win 2,5 times your bet. Only if the dealer hits BlackJack it will be seen as a tie, otherwise you win.
- Five Card Charlie means hitting 3 cards after your opening hand and you are still below 21. You win 2,5 times your bet. There are no exceptions.
- If your cards are equal to the card's of the dealer, you won't win or lose your bet. Just takes back your bet.
- Players who have more points than the dealer, under 21, wins. You win 2 times your bet.
