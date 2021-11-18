# php-roguelike

Worst game on the planet.

## What is this?

[PHP Roguelike](https://github.com/sebastianluczak/php-roguelike) is a console (CLI) based game driven by rogulike scheme based progression.
You'll die. A lot. Embrace it and go further and further, discovering new bosses and trying to beat high score.

This game is done as a part of side-project to show'up some programming skills in PHP. You're welcome to give ideas and put some PR's.

## Trivia

> J: Mom, can we have ADOM at home?
> 
> M: But we have ADOM at home...
> 
> Meanwhile, ADOM at home:

![Screenshot](docs/images/screenshot.png)

## Purpose

Ok, so I'm a middle aged PHP developer looking for a project or a job. This project should give me head start. :)
Also, it's so fun to fiddle with some side-project and have a time sink for myself.

## Run & build

#### Prerequisites:
- Docker with Docker Compose
- Terminal Emulator capable of displaying rich text and XTERM256 support

```shell
cd ~
git clone https://github.com/sebastianluczak/php-roguelike.git
cd php-roguelike
chmod +x bin/*
bin/build.sh
```

## Keymap

```shell
W/A/S/D - Movement of character
I - Player Interface (WIP)
L - show Leaderbords
Q - quits the game
H - use Healing Potion

-- 
DEV_MODE
R - regenerates the level
P - switches DevMode (required for some output and commands)
R - regenerate a map (DEV_MODE required) 
M - DevRoom :D (╯°□°)╯︵ ┻━┻ 
```

## Win/Lose conditions

As all roguelikes - you'll die eventually. It's just part of the process. Your score will be written down in LeaderBoards. Try to achieve best score!

## Legend

There're multiple tiles with scattered logic all around the board.

```shell
# - Chest or RareChest - guaranteed drop of Item, Gold or Health Potion
$ - Mysterious Man - can do good things for you
* - Boss Fight - be prepared!
o - Altar, drop some gold for a buff or curse
```
TODO: {LEGEND_OF_TILES_AND_SUMMARY_OF_MECHANICS}

## Win strategy
1. Go for Luck > 1
2. Hoard Chests for life
3. Try to stay alive (visit ShopTile and AltarTile as often as you can)
4. Fight small creatures (luck based)
5. Visit Chests
6. Go for max Health Regen from buffs
7. (wait for it, wander around)
8. Fight BOSS
9. Die and repeat
10. PROFIT