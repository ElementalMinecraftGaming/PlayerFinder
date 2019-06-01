<?php

namespace ElementalMinecraftGaming\PlayerFinder;


use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\{Level,Position};
use pocketmine\utils\Config;
use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;

class Main extends PluginBase implements Listener {
    
    public function onEnable() {
         @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onMove(PlayerMoveEvent $event) {
        $frozen = $this->config->getAll(true);
        foreach ($frozen as $froze) {
            $no = $event->getPlayer($froze);
            $event->setCancelled();
            $no->sendMessage(TextFormat::BLUE . "Frozen: You can't move.");
        }
    }
    
    

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (strtolower($command->getName()) == "find") {
            if ($sender->hasPermission("find.player")) {
                if ($sender instanceof Player) {
                    if (isset($args[0])) {
                        if (isset($args[1])) {
                            if ($args[0] == "tp") {
                                $player = $args[1];
                                $world = $this->getServer()->getPlayer($player)->getLevel()->getFolderName();
                                $z = $this->getServer()->getPlayer($player)->getZ();
                                $x = $this->getServer()->getPlayer($player)->getX();
                                $y = $this->getServer()->getPlayer($player)->getY();
                                $this->getServer()->getPlayer($player)->teleport(new Position($x, $y, $z, $this->getServer()->getLevelByName($world)));
                                $sender->sendMessage(TextFormat::GREEN . "Teleported to: $world, $x, $y, $z");
                                return true;
                            } elseif ($args[0] == "find") {
                                $player = $args[1];
                                 $world = $this->getServer()->getPlayer($player)->getLevel()->getFolderName();
                                $z = $this->getServer()->getPlayer($player)->getZ();
                                $x = $this->getServer()->getPlayer($player)->getX();
                                $y = $this->getServer()->getPlayer($player)->getY();
                                $sender->sendMessage(TextFormat::GREEN . "Location found: $world, $x, $y, $z");
                            } elseif ($args[0] == "world") {
                                $player = $args[1];
                                $world = $this->getServer()->getPlayer($player)->getLevel()->getFolderName();
                                $sender->sendMessage(TextFormat::GREEN . "World Located: $world");
                            } elseif ($args[0] == "troll") {
                                if ($sender->hasPermission("opfind.player")) {
                                $player = $args[1];
                                 $world = $this->getServer()->getPlayer($player)->getLevel()->getFolderName();
                                $z = $this->getServer()->getPlayer($player)->getZ();
                                $x = $this->getServer()->getPlayer($player)->getX();
                                $y = $this->getServer()->getPlayer($player)->getY();
                                $this->getServer()->getPlayer($player)->getLevel()->setBlock($this->getServer()->getPlayer($player)->floor(), Block::get(Block::LAVA));
                                $sender->sendMessage(TextFormat::GREEN . "Location found: $world, $x, $y, $z");
                                $sender->sendMessage(TextFormat::BLUE . "Player Trolled");
                                } else {
                                    $sender->sendMessage(TextFormat::RED . "Requires opfind.player permission");
                                }
                            } elseif ($args[0] == "freeze") {
                                if ($sender->hasPermission("opfind.player")) {
                                $player = $this->getServer()->getPlayer($args[1])->getName();
                                 $world = $this->getServer()->getPlayer($player)->getLevel()->getFolderName();
                                $z = $this->getServer()->getPlayer($player)->getZ();
                                $x = $this->getServer()->getPlayer($player)->getX();
                                $y = $this->getServer()->getPlayer($player)->getY();
                                $this->config->set($player, ["frozen"]);
                                $this->config->save();
                                $this->config->reload(true);
                                $sender->sendMessage(TextFormat::GREEN . "Location found: $world, $x, $y, $z");
                                $sender->sendMessage(TextFormat::BLUE . "Player is frozen");
                                } else {
                                    $sender->sendMessage(TextFormat::RED . "Requires opfind.player permission");
                                }
                            } elseif ($args[0] == "unfreeze") {
                                if ($sender->hasPermission("opfind.player")) {
                                $player = $this->getServer()->getPlayer($args[1])->getName();
                                unset($this->config->$player);
                                $this->config->save();
                                $this->config->reload(true);
                                $world = $this->getServer()->getPlayer($player)->getLevel()->getFolderName();
                                $z = $this->getServer()->getPlayer($player)->getZ();
                                $x = $this->getServer()->getPlayer($player)->getX();
                                $y = $this->getServer()->getPlayer($player)->getY();
                                $sender->sendMessage(TextFormat::GREEN . "Location found: $world, $x, $y, $z");
                                } else {
                                    $sender->sendMessage(TextFormat::RED . "Requires opfind.player permission");
                                }
                            } else {
                                $sender->sendMessage(TextFormat::RED . "Commands: \n/findp tp {name}\n/find find {player}\n/find world {player}\n/find troll {name}\n/findp freeze {name}\n/findp unfreeze {name}");
                            }
                        } else {
                            $sender->sendMessage(TextFormat::RED . "Commands: \n/findp tp {name}\n/find find {player}\n/find world {player}\n/find troll {name}\n/findp freeze {name}\n/findp unfreeze {name}");
                        }
                    } else {
                        $sender->sendMessage(TextFormat::RED . "Commands: \n/findp tp {name}\n/find find {player}\n/find world {player}\n/find troll {name}\n/findp freeze {name}\n/findp unfreeze {name}");
                    }
                } else {
                    $sender->sendMessage(TextFormat::RED . "Must run in-game!");
                }
            } else {
                $sender->sendMessage(TextFormat::RED . "No permissions!");
                return false;
            }
        }
        return false;
    }

}
