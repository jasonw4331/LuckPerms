![](https://raw.githubusercontent.com/LuckPerms/branding/master/banner/banner.png "Banner")

# LuckPerms

[![Discord](https://img.shields.io/discord/241667244927483904.svg?label=discord&logo=discord)](https://discord.gg/luckperms)

LuckPerms is a permissions plugin for Minecraft servers. It allows server admins to control what features players can
use by creating groups and assigning permissions.

The latest downloads, wiki & other useful links can be found on the project homepage
at [luckperms.net](https://luckperms.net/).

It is:

* **fast** - written with performance and scalability in mind.
* **reliable** - trusted by thousands of server admins, and the largest of server networks.
* **easy to use** - setup permissions using commands, directly in config files, or using the web editor.
* **flexible** - supports a variety of data storage options, and works on lots of different server types.
* **extensive** - a plethora of customization options and settings which can be changed to suit your server.
* **free** - available for download and usage at no cost, and permissively licensed so it can remain free forever.

For more information, see the wiki article on [Why LuckPerms?](https://luckperms.net/wiki/Why-LuckPerms)

## Contributing

#### Work In Progress

The project can be separated into 4 main features and 8 subfeatures.

* [ ] **Groups**
    * [ ] Prefix/Suffix
    * [ ] Tracks
    * [ ] Default Groups
    * [ ] Contexts
    * [ ] Weights
* [ ] **Permission Management**
    * [ ] Commands
    * [ ] Web Editor
    * [ ] Storage
* [ ] **Verbose** - The verbose system allows you to monitor permission checks occurring in real time
* [ ] **Meta** - LuckPerms supports metadata, which can be applied to users and groups. These are used to store
  arbitrary data, which can be used by other plugins.

#### Project Layout

The project is split up into a few separate modules.

* **API** - The public, semantically versioned API used by other plugins wishing to integrate with and retrieve data
  from LuckPerms. This module (for the most part) does not contain any implementation itself, and is provided by the
  plugin.
* **Common** - The common module contains most of the code which implements the respective LuckPerms plugins. This
  abstract module reduces duplicated code throughout the project.
* **Bukkit, BungeeCord, Fabric, Forge, Nukkit, Sponge & Velocity** - Each use the common module to implement plugins on
  the respective server platforms.

## License

LuckPerms is licensed under the permissive MIT license. Please
see [`LICENSE.txt`](https://github.com/lucko/LuckPerms/blob/master/LICENSE.txt) for more info.