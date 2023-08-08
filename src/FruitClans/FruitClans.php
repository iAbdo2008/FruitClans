<?php

namespace FruitClans;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;


class FruitClans extends PluginBase implements Listener{

    private static $instance;
    private $clanowners;
    private $clantags;
    private $clanrequests;
    private $clanmembers;
    private $player_data;

    public function onEnable() : void {

        @mkdir($this->getDataFolder() . "FruitClans/");
        @mkdir($this->getDataFolder() . "FruitClansPlayers/");
        $this->getLogger()->info($this->ReplaceColors("&7| &6Fruit§3Clans &7- &aEnabled &7|"));
        //$this->getLogger()->info($this->ReplaceColors("&7| &6Fruit§3Clans &7- &6Developed By oPinqzz, For &cPaid Commissions , \n&6Go to My Discord&7: &3op_n#0 &7|"));

        $this->getCommand("clan")->setExecutor(new FruitCommands, $this);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->clanowners = new Config($this->getDataFolder() . "FruitClans/clanowners.yml", 2);
		$this->clantags = new Config($this->getDataFolder() . "FruitClans/clantags.yml", 2);
		$this->clanrequests = new Config($this->getDataFolder() . "FruitClans/clanrequests.yml", 2);
		$this->clanmembers = new Config($this->getDataFolder() . "FruitClans/clanmembers.yml", 2);

        self::$instance = $this;
    }

    public function onDisable() : void {
        $this->getLogger()->info($this->ReplaceColors("&7| &6Fruit§3Clans &7- &cDisabled &7|"));
        //$this->getLogger()->info($this->ReplaceColors("&7| &6Fruit§3Clans &7- &6Developed By oPinqzz, For &cPaid Commissions , \n&6Go to My Discord&7: &3op_n#0 &7|"));
    }

    public static function getInstance() : self {
        return self::$instance;
    }

    public function onJoin(PlayerJoinEvent $e){
        $player = $e->getPlayer();
        
        $this->player_data = new Config($this->getDataFolder() . "FruitClansPlayers/" . strtolower($player->getName()) . ".yml", 2, array(
            "Name" => $player->getName(),
            "Clan" => "None",
        ));
    }

    public function getData(Player $player = null, $player_name = null) {
        if($player == null) {
            return $this->player_data = new Config($this->getDataFolder() . "FruitClansPlayers/" . strtolower($player_name) . ".yml", 2, array(
                "Name" => $player_name,
                "Clan" => "None",
            ));
        } else {
            return $this->player_data = new Config($this->getDataFolder() . "FruitClansPlayers/" . strtolower($player->getName()) . ".yml", 2, array(
                "Name" => $player->getName(),
                "Clan" => "None",
            ));
        }
    }


    public function ReplaceColors(String $s) {
        $new_s = str_replace("&", "§", $s);
        return $new_s;
    }


    public function sendHelpMenu(CommandSender $sender) {
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &6Fruit§3Clans  &7- &aHelp Menu"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Creating a Clan, Type &7: &6/clan create {clan_name} {clan_prefix}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Deleting a Clan, Type &7: &6/clan delete {clan_name}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Accepting a Clan Invitation, Type &7: &6/clan accept {clan_name}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Declining a Clan Invitation, Type &7: &6/clan decline {clan_name}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Inviting Someone to The Clan, Type &7: &6/clan invite {player_name}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Transferring The OwnerShip of The Clan, Type &7: &6/clan transfer {player_name}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Editing The Clan Tag/Prefix, Type &7: &6/clan edittag {clan_name} {clan_new_prefix}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Quitting a Clan, Type &7: &6/clan quit {clan_name}"));
        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &3For Kicking Someone From The Clan, Type &7: &6/clan kick {player_name}"));
    }

    public function createClan(CommandSender $sender, String $c_name, String $c_prefix) {
        $data = $this->getData(null, $sender->getName());
        if(!isset($c_name)){
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, Invaild Input, Please Use Command &7: &6/clan help"));
            return false;
        } else {
            if(!isset($c_prefix)){
                $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, Invaild Input, Please Use Command &7: &6/clan help"));
                return false;
            } else {
                if($this->clanowners->exists($c_name, true)){
                    $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Clan Already Exists"));
                    return false;
                } else {
                    foreach($this->clanowners->getAll() as $p) {
                        if ($p == $sender->getName()) {
                            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Can't Own More Than 1 Clan"));
                            return false;
                        }
                    } 
                    $clan_name = strval($c_name);
                    $clan_tag = strval($c_prefix);

                    $this->clanowners->set($clan_name, $sender->getName());
                    $this->clantags->set($clan_name, $clan_tag);
                    $this->clanmembers->set($clan_name, $sender->getName() . ", ");
                    $data->set("Clan", $c_name);
                    $data->save();
                    $this->clanowners->save();
                    $this->clantags->save();
                    $this->clanmembers->save();

                    $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aSuccessfuly Created Your Clan, By Name : $clan_name, By Tag : $clan_tag"));
                }

            }
        }
    }


    public function deleteClan(CommandSender $sender) {

        $c_name = $this->getData($sender)->get("Clan");

        if($c_name == "None") {
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Don't Own Any Clan To Delete"));
            return false;
        } else {
            $c_name = strval($c_name);
            if($this->clanowners->get($c_name) == $sender->getName()) {
                
                $names = $this->clanmembers->get($c_name);
                $array_names = explode(", ", $names);
                foreach($array_names as $name) {
                    $pl_data = $this->getData(null, $name);
                    $pl_data->set("Clan", "None");
                    $pl_data->save();
                }

                $this->clanowners->remove($c_name);
                $this->clantags->remove($c_name);
                $this->clanmembers->remove($c_name);
                $this->clanrequests->remove($c_name);
                $this->clanowners->save();
                $this->clantags->save();
                $this->clanmembers->save();
                $this->clanrequests->save();

    
                $sender->sendMessage($this->ReplaceColors("§7| &6Fruit§3Clans  &7» &aSuccessfuly &4Deleted &aYour Clan, This Action Cannot Be Undone"));
            } else {
                $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, An Error Occured, You Don't Own This Clan to Delete It"));
                return false;
            }
        }
    }

    

    public function inviteToClan(CommandSender $sender, String $player_name) {
        $sender_data = $this->getData(null, $sender->getName());

        if(!isset($player_name)){
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, Invalid Input, Please Use Command &7: &6/clan help For More Info"));
            return false;
        } else {
            if($this->clanowners->get($sender_data->get("Clan")) == $sender->getName()){
                $pname = $player_name;

                $target = $this->getServer()->getPlayerExact($pname);

                if($target == null){
                    $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Player Seems To Be Offline"));
                    return false;
                } else {
                    $target_data = $this->getData(null, $target->getName());

                    if($target_data->get("Clan") != "None"){
                        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Player is at Another Clan"));
                        return false;
                    } else {
                        if($target->getName() == $sender->getName()){
                            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, An Error Occured, You Can't Invite Yourself"));
                            return false;
                        } else {
                            $this->clanrequests->set($sender_data->get("Clan"), $this->clanrequests->get($sender_data->get("Clan")) . $target->getName() . ", ");
                            $this->clanrequests->save();
                            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aSuccessfully Sent Invite to the Player {$target->getName()}"));
                            $clan_name = $sender_data->get("Clan");
                            $target->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &eYou Have been invite from {$sender->getName()} to join the Clan By Name &b{$clan_name}"));
                        }
                    }
                }
            } else {
                $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Don't Own This Clan To Invite Someone"));
                return false;
            }
        }
    }

    public function transferOwnership(CommandSender $sender, String $player_name) {
        $sender_data = $this->getData(null, $sender->getName());
        if(!isset($player_name)){
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, Invalid Input, Please Use This Command For More Info&7: &6/clan help"));
            return false;
        } else {
            $pname = strval($player_name);

            $target = $this->getServer()->getPlayerExact($pname);

            if($target == null){
                $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Player Seems To Be offline"));
                return false;
            } else {
                $target_data = $this->getData(null, $target->getName());
                if($target_data->get("Clan") == "None" || $target_data->get("Clan") == $sender_data->get("Clan")){
                    $this->clanowners->set($sender_data->get("Clan"), $target->getName());
                    $this->clanowners->save();

                    $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aTransferred The Ownership to the player"));
                    $clan_tag = $this->clantags->get($sender_data->get("Clan"));
                    $target->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aYou Have Gained the Ownership of $clan_tag Clan"));
                    $target_data->set("Clan", $sender_data->get("Clan"));
                    $target_data->save();
                } else {
                    $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Can't Transfer The Ownership of the clan to another one is at other clan"));
                    return false;
                }
            }
        }
    }

    public function acceptInvite(CommandSender $sender, String $c_name) {
        if(!isset($c_name)){
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, An Error Occured, Please Use This Command For More Info&7: &6/clan help"));
            return false;
        } else {
            $sender_data = $this->getData(null, $sender->getName());
            $clan_name = strval($c_name);

            if($this->clanrequests->get($clan_name) == null){
                $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Clan Doesn't Exist"));
                return false;
            } else {
                $exploded_requestes = explode(", ", $this->clanrequests->get($clan_name));
                foreach($exploded_requestes as $requests){
                    if(strpos($requests, $sender->getName()) !== false){
                        $this->clanrequests->set($clan_name, str_replace($sender->getName() . ", ", '', $this->clanrequests->get($clan_name)));
                        $this->clanrequests->save();
                        $this->clanmembers->set($clan_name, $this->clanmembers->get($clan_name) . $sender->getName() . ", ");
                        $this->clanmembers->save();
                        $sender_data->set("Clan", $clan_name);
                        $sender_data->save();

                        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aSuccessfully Accepted the clan invitation"));
                    } else {
                        //$sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Aren't Invited To This Clan"));
                        return false;

                    }
                }
            }
            
        }
    }


    public function declineInvite(CommandSender $sender, String $c_name) {
        if(!isset($c_name)){
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, Invalid Input, Please Use This Command For More Info&7: &6/clan help"));
            return false;
        } else {
            $sender_data = $this->getData(null, $sender->getName());
            $clan_name = strval($c_name);

            if($this->clanrequests->get($clan_name) == null){
                $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Clan Doesn't Exist"));
                return false;
            } else {
                $exploded_requestes = explode(", ", $this->clanrequests->get($clan_name));
                foreach($exploded_requestes as $requests){
                    if(strpos($requests, $sender->getName()) !== false){
                        $this->clanrequests->set($clan_name, str_replace($sender->getName() . ", ", '', $this->clanrequests->get($clan_name)));
                        $this->clanrequests->save();

                        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aSuccessfully Declined Clan Invitation"));
                        
                    } else {
                        //$sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Aren't Invited To This Clan"));
                        return false;

                    }
                }
            }
        }
    }


    public function quitFromClan(CommandSender $sender) {
        $sender_data = $this->getData(null, $sender->getName());

        if($sender_data->get("Clan") !== "None"){
            $members_of_the_clan = $this->clanmembers->get($sender_data->get("Clan"));
            $each_member = explode(", ", $members_of_the_clan);
            if($this->clanowners->get($sender_data->get("Clan")) == $sender->getName()){
                $sender->sendMessaage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Can't Quit a Clan That You Own"));
                return false;

            } else {
                foreach($each_member as $m){
                    if(strpos($m, $sender->getName()) !== false){
                        $this->clanmembers->set($sender_data->get("Clan"), str_replace($sender->getName() . ", ", '', $this->clanmembers->get($sender_data->get("Clan"))));
                        $this->clanmembers->save();
                        
                        $sender_data->set("Clan", "None");
                        $sender_data->save();

                        $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aSuccessfully Quited The Clan"));
                    }
                }
            }
        } else {
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, You Didn't Join A Clan To Execute This Command"));
            return false;
        }
    }

    public function kickFromClan(CommandSender $sender, String $player_name) {
        if(!isset($player_name)){
            $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, Error Has Occured, Please Type This Command For More Info&7: &6/clan help"));
            return false;
        } else {
            $pname = strval($player_name);
            $target = $this->getServer()->getPlayerExact($pname);

            if($target == null){
                $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Player Seems To Be Offline"));
                return false;
            } else {
                $sender_data = $this->getData(null, $sender->getName());
                $target_data = $this->getData(null, $target->getName());

                if($target_data->get("Clan") == $sender_data->get("Clan") && $this->clanowners->get($sender_data->get("Clan")) == $sender->getName()){
                    $this->clanmembers->set($sender_data->get("Clan"), str_replace($target->getName() . ", ", '', $this->clanmembers->get($sender_data->get("Clan"))));
                    $this->clanmembers->save();
                    $target_data->set("Clan", "None");
                    $target_data->save();
                    $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &aSuccessfully You Kicked The Player Out of the Clan"));
                } else {
                    $sender->sendMessage($this->ReplaceColors("&7| &6Fruit§3Clans  &7» &cSorry, This Member Isn't At Your Clan Or You Don't Have Ownership to kick this member"));
                    return false;
                }
            }
        }
    }





    

    

}