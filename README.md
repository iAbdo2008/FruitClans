# FruitClans

```This Plugin Is Maintained By UtriX Network , All Copyrights are reserved.```

The First &amp; Best Clans Plugin For Pocketmine-MP 5 !

- Introduces an API For Developers
- New Of It's Type

# How To Use API

```php
$fruit_clans = FruitClans::getInstance();
```
- This Is For Importing the Class

# API

- For API , These are the available functions:

- ```sendHelpMenu(CommandSender $sender),``` - **For Sending Help Menu**
- ```createClan(CommandSender $sender, String $clan_name, String $clan_prefix)``` - **For Creating a Clan**
- ```deleteClan(CommandSender $sender)``` - **For Deleting a Clan**
- ```inviteToClan(CommandSender $sender, String $player_name)``` - **For Inviting to a Clan**
- ```transferOwnership(CommandSender $sender, String $player_name)``` - **For Transferring an Ownership of a Clan**
- ```acceptInvite(CommandSender $sender, String $clan_name)``` - **For Accepting Invite**
- ```declineInvite(CommandSender $sender, String $clan_name)``` - **For Declining a Clan Invite**
- ```quitFromClan(CommandSender $sender)``` - **For Quiting a Clan**
- ```kickFromClan(CommandSender $sender, String $player_name)``` - **For Kicking Some One From The Clan**

