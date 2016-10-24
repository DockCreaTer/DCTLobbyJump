<?php

namespace PressureJump;

use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginManager;
use pocketmine\Server;
use pocketmine\utils\Config;

class pressurejump extends PluginBase implements Listener{

    public function onDisable() {
        $this->saveDefaultConfig();
        System_out_println("[PressurePush] Disabled");
    }

    public function onEnable() {
        System_out_println("[PressurePush] Enabled");
        $this->getServer->getPluginManager()->registerEvents(this, this);
        $this->getConfig()->options()->copyDefaults(true);
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender sender, Command cmd, String cmdLabel, String[] args) {
        Player $player = (Player) sender;
        if ($player->hasPermission("pp.admin")) {
            $player->sendMessage(TEXTFORMAT::RED + "You don't have permission to use this command");
            return true;
        }

        if (cmd.getName().equalsIgnoreCase("pressurepush")) {
              (StringTag->getConfig()->getStringList("help")) {
                $player->sendMessage(TEXTFORMAT->translateAlternateColorCodes('&', s).replace("{version}", getDescription()->getVersion()));
            }
            return true;
        }

        if (cmd.getName().equalsIgnoreCase("ppload")) {
            if (sender->isOp()) 
                return true;
            $this->reloadConfig();
            $this->saveConfig();
            if (sender instanceof Player) {
                createactive->add(player->getName());
            }
            sender->sendMessage(TEXTFORMAT::AQUA + "-> PressurePush config has been reloaded <-");
        }

        if (cmd.getName().equalsIgnoreCase("ppcreate")) {
            if (args.length == 0) {
                for (String s : getConfig()->getStringList("help")) {
                    player.sendMessage->((TEXTFORMAT->translateAlternateColorCodes('&', s).replace("{version}", getDescription().getVersion()));
                }
            } else if (args[0].equalsIgnoreCase("on") && player.hasPermission("pp.create")) {
                createactive->add(player->getName());
                player->sendMessage(TEXTFORMAT::GOLD + "Place the pressure plate somewhere to make it a PressurePush plate, type the command again to disable it");
                if (getConfig()->getBoolean("UnlimitedPlates") == true) {
                    player->getInventory()->addItem(new ItemStack(Material.STONE_PLATE, -1));
                    player->getInventory()->addItem(new ItemStack(Material.WOOD_PLATE, -1));
                }
            } else if (args[0].equalsIgnoreCase("off") && player.hasPermission("pp.create")) {
                createactive.remove(player.getName());
                player.sendMessage(ChatColor.RED + "You've de-toggled the creation of PressurePush plates!");
                if (getConfig().getBoolean("UnlimitedPlates") == true) {
                    player->getInventory()->removeItem(new ItemStack(Material.STONE_PLATE, -1));
                    player->getInventory()->removeItem(new ItemStack(Material.WOOD_PLATE, -1));
                }
            } else {
                player.sendMessage(ChatColor.RED + "You don't have permission to use this command");
                return true;
            }
        }
        return true;
    }

    @EventHandler(ignoreCancelled = true)
    public function BlockPlaceEvent(BlockPlaceEvent event) {
        Player p = event.getPlayer();
        if (!p.hasPermission("pp.create")) {
            return;
        }
        if (!createactive.contains(p.getName())) {
            return;
        }
        if (event.getBlock().getType() == Material.STONE_PLATE || event.getBlock().getType() == Material.WOOD_PLATE) {
            Location location = event.getBlock().getLocation();
            String loc = location.getBlockX() + "-" + location.getBlockY() + "-" + location.getBlockZ() + "-" + location.getWorld().getName();

            p.sendMessage(ChatColor.GREEN + "You've successfully made a PressurePush Plate");

            List<String> locs = getConfig().getStringList("Plates.location");
            if (!locs.contains(loc)) {
                locs.add(loc);
                getConfig().set("Plates.location", locs);
                saveConfig();
            }
        }
    }

    @EventHandler(ignoreCancelled = true)
    public function BlockBreakEvent(BlockBreakEvent event) {
        Player p = event.getPlayer();
        if (!p.hasPermission("pp.destroy")) {
            return;
        }
        Location location = event.getBlock().getLocation();
        String loc = location.getBlockX() + "-" + location.getBlockY() + "-" + location.getBlockZ() + "-" + location.getWorld().getName();
        List<String> locs = getConfig().getStringList("Plates.location");
        if (!locs.contains(loc)) {
            return;
        }
        if (event.getBlock().getType() == Material.STONE_PLATE || event.getBlock().getType() == Material.WOOD_PLATE) {
            locs.remove(loc);
            getConfig().set("Plates.location", locs);
            saveConfig();

            event.getPlayer().sendMessage(ChatColor.RED + "You have removed a PressurePush plate");
        }
    }

    @EventHandler
    public function damageEvent(EntityDamageEvent e) {
        if (e.getCause() == DamageCause.FALL && e.getEntity() instanceof Player) {
            Player p = (Player) e.getEntity();
            if (disableFall.contains(p.getName())) {
                e.setCancelled(true);
                disableFall.remove(p.getName());
            }
        }
    }

    @EventHandler(ignoreCancelled = true)
    public function onPressurePlateStep(PlayerInteractEvent e) {
        Player p = e.getPlayer();
        if (!p.hasPermission("pp.use")) {
            return;
        }
        if (e.getAction().equals(Action.PHYSICAL) && (p.hasPermission("pp.use") && e.getClickedBlock().getType() == Material.STONE_PLATE
                || e.getClickedBlock().getType() == Material.WOOD_PLATE)) {
            double strength = getConfig().getDouble("Strength");
            double up = getConfig().getDouble("Up");
            Location location = e.getClickedBlock().getLocation();
            String loc = location.getBlockX() + "-" + location.getBlockY() + "-" + location.getBlockZ() + "-" + location.getWorld().getName();
            List<String> locs = getConfig().getStringList("Plates.location");
            if (!locs.contains(loc)) {
                return;
            }
            if (getConfig().getInt("Sound") == 0) {
                Vector v = p.getLocation().getDirection().multiply(strength).setY(up);
                p.setVelocity(v);
                e.setCancelled(true);
            }

            if (getConfig().getInt("Sound") == 1) {
                Vector v = p.getLocation().getDirection().multiply(strength).setY(up);
                p.setVelocity(v);
                p.playSound(p.getLocation(), Sound.ENTITY_ENDERDRAGON_HURT, 10.0F, 2.0F);
                e.setCancelled(true);
            }

            if (getConfig().getInt("Sound") == 2) {
                Vector v = p.getLocation().getDirection().multiply(strength).setY(up);
                p.setVelocity(v);
                p.playSound(p.getLocation(), Sound.ENTITY_IRONGOLEM_ATTACK, 10.0F, 2.0F);
                e.setCancelled(true);
            }

            if (getConfig().getInt("Sound") == 3) {
                Vector v = p.getLocation().getDirection().multiply(strength).setY(up);
                p.setVelocity(v);
                p.playSound(p.getLocation(), Sound.ENTITY_ENDERDRAGON_FLAP, 10.0F, 2.0F);
                e.setCancelled(true);
            }

            if (getConfig().getInt("Sound") == 4) {
                Vector v = p.getLocation().getDirection().multiply(strength).setY(up);
                p.setVelocity(v);
                p.playSound(p.getLocation(), Sound.ENTITY_BLAZE_DEATH, 1.0F, 1.0F);
                e.setCancelled(true);
            }
            disableFall.add(p.getName());
        }
    }
}
 
