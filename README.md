# <img src="https://i.natfrp.org/3d939a311fe6bf031f25c4eeefda9c39.png" align="right" style="width: 256px">PHPMC-7
Open source | Multi-Platform | Multi-nodes | FTP support | BungeeCord Support

Are you still looking for a simple and convenient Minecraft Server Manager? PHPMC 7 may be your best choice!

中文介绍 ReadMe：[[README_CN.md]](https://github.com/kasuganosoras/PHPMC7/blob/master/README_CN.md)

> PHPMC 7 is a powerful Minecraft Server Manager developed by Akkariin

### Introduction
Hello, I am Akkariin. This is my first Minecraft server manager project.

I always wanted to build a Minecraft server manager a long ago, so I recently spent some time on developing the fascinating PHPMC 7 Minecraft server manager panel.

### Features
> 1. Complete Minecraft server management capabilities
> 2. Works on multiple platforms, it works on Windows and Linux, even Android phones
> 3. Supports multiple nodes and distributed daemons on different servers
> 4. Supports FTP file transfer system
> 5. Supports Docker Containers(With some modifications on server starting command)
> 6. Supports any game that allows you to run your own server.

### Security & Performance
We do not use MD5 hashing because nowadays its possible to be reverse decrypted. We use irreversible hashing function like BCrypt.

We use JAVA I/O Stream to send commands and read & write logs, it makes the stream process more stable with higher efficiency.

PHPMC supports Docker to run servers, but it is not recommanded to use PHPMC 7 for a commercial purpose.

PHPMC supports privilege subdivision and almost every single operation can be controlled by privilege subdivision system.

### Demo
* 简体中文: [https://demo.phpmc.cn/?lang=zh_CN](https://demo.phpmc.cn/?lang=zh_CN)
* 繁體中文: [https://demo.phpmc.cn/?lang=zh_TW](https://demo.phpmc.cn/?lang=zh_TW)
* English: [https://demo.phpmc.cn/?lang=en_US](https://demo.phpmc.cn/?lang=en_US)
* Russian: [https://demo.phpmc.cn/?lang=ru_RU](https://demo.phpmc.cn/?lang=ru_RU)

The demo username and password are `admin`

The demo is only for you to see how the system works, and layout demonstration. 

The demo does not always update to latest version. Please install the latest PHPMC to try out all features.

### Multiple languages
PHPMC 7 now supports multiple languages, and you can switch to system languages like `en_US`, `zh_CN` and more languages in PHPMC 7 settings.

More languages: https://github.com/kasuganosoras/PHPMC7-Multi-Language

### Licences
This project uses the GNU General Public License v3.0 open source.

You may use, modify and distribute it arbitrarily, subject to the agreement.
