<?php

namespace FruitClans;

use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;

use FruitClans\FruitClans;

class FruitCommands implements CommandExecutor {

    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {

        if($cmd->getName() == "clan"){
            if(!isset($args[0])){
                $fruitclans = FruitClans::getInstance();
                $sender->sendMessage($fruitclans->ReplaceColors("&7| &l&bRoys&fMc &7» &cSorry, Invaild Input, Please use Command &7: &6/clan help"));
                return false;
            } else {
                if ($args[0] == "help"){
                    $fruitclans = FruitClans::getInstance()->sendHelpMenu($sender);
                } 

                if($args[0] == "create") {
                    if(!isset($args[1]) || !isset($args[2])) {
                        $sender->sendMessage(FruitClans::getInstance()->ReplaceColors("&7| &l&bRoys&fMc &7» &cSorry, Invaild Input, Please Use Command &7: &6/clan help"));
                        return false;
                    } else {
                        FruitClans::getInstance()->createClan($sender, strval($args[1]), strval($args[2]));
                    }
                }

                if($args[0] == "delete") {
                    FruitClans::getInstance()->deleteClan($sender);
                }

                if($args[0] == "invite"){
                    if(!isset($args[1])) {
                        $sender->sendMessage(FruitClans::getInstance()->ReplaceColors("&7| &l&bRoys&fMc &7» &cSorry, Invaild Input, Please Use Command For More Info&7: &6/clan help"));
                        return false;
                    } else {
                        FruitClans::getInstance()->inviteToClan($sender, strval($args[1]));
                    }
                }

                if($args[0] == "accept") {
                    if(!isset($args[1])) {
                        $sender->sendMessage(FruitClans::getInstance()->ReplaceColors("&7| &l&bRoys&fMc &7» &cSorry, Invaild Input, Please Use Command For More Info&7: &6/clan help"));
                        return false;
                    } else {
                        FruitClans::getInstance()->acceptInvite($sender, strval($args[1]));
                    }
                }

                if($args[0] == "decline") {
                    if(!isset($args[1])) {
                        $sender->sendMessage(FruitClans::getInstance()->ReplaceColors("&7| &l&bRoys&fMc &7» &cSorry, Invaild Input, Please Use Command For More Info&7: &6/clan help"));
                        return false;
                    } else {
                        FruitClans::getInstance()->declineInvite($sender, strval($args[1]));
                    }
                }

                if($args[0] == "transfer") {
                    if(!isset($args[1])) {
                        $sender->sendMessage(FruitClans::getInstance()->ReplaceColors("&7| &l&bRoys&fMc &7» &cSorry, Invaild Input, Please Use Command For More Info&7: &6/clan help"));
                        return false;
                    } else {
                        FruitClans::getInstance()->transferOwnership($sender, strval($args[1]));
                    }
                }

                if($args[0] == "quit") {
                    FruitClans::getInstance()->quitFromClan($sender);
                }

                if($args[0] == "kick") {
                    if(!isset($args[1])) {
                        $sender->sendMessage(FruitClans::getInstance()->ReplaceColors("&7| &l&bRoys&fMc &7» &cSorry, Invaild Input, Please Use Command For More Info&7: &6/clan help"));
                        return false;
                    } else {
                        FruitClans::getInstance()->kickFromClan($sender, strval($args[1]));
                    }
                }
            }


        }


        return true;
    }


}