# PHPMC-7
开源 | 跨平台 | 分布式 | 内置 FTP | 支持群组 | 支持 Docker

欢迎使用 PHPMC 7，这是一款 Minecraft 服务器管理系统，由 Akkariin 开发。

![PHPMC7-Logo](https://i.natfrp.org/90652ab275ce942c71f00eb250104225.png)

### 简介
嗨，我是超级鸽子王Akkariin，这次我给大家带来的是我很久没更新（对的，非常久）的 PHPMC 系列软件。

之前的 PHPMC 3 由于开源协议问题被删帖，不过这次不用担心。

之所以一下跨这么大个版本呢...主要是因为时间太长了。

最新的 PHPMC 7 拥有很多强大的功能，具体有哪些亮点呢？我们一起往下看。

### 主要功能
> 1. 完整的 Minecraft 服务器管理功能
> 2. 支持跨平台，Windows 和 Linux 都能运行，甚至可以运行在手机上
> 3. 支持多节点，轻松实现分布式
> 4. 内置 FTP 文件传输功能
> 5. 支持 Docker 容器（通过命令调用方式启动容器）
> 6. 可以运行除了 Minecraft 以外的其他游戏服务器

### 安全性
经过两年多的时间，PHPMC 7 已经非常安全和稳定了，PHPMC 7 不再使用 MD5，而是改为使用 BCrypt。

PHPMC 7 抛弃了以往的 PHP Daemon 和 Rcon 这种低效率的命令执行方式，改为 Java Daemon 和标准输入输出。

理论上面板可以执行任何命令行，所以建议您不要用于商业出租，或者使用 Docker 容器并拒绝普通用户设置启动命令参数。

PHPMC 7 支持权限细分，每个操作都可以设定权限。

。。。懒得写了。。Markdown真麻烦。。。

看这里吧：http://www.mcbbs.net/thread-819800-1-1.html

### 多语言 Multi-language
PHPMC 7 目前已支持多语言，您可以在 PHPMC 7 设置中切换系统语言，例如 `en_US`。

PHPMC 7 now support multi-language, you can change the system language in setting such as `en_US`.

俄罗斯语：https://github.com/maxim19116/PHPMC7 由 @maxim19116 翻译。

更多语言 More：https://github.com/kasuganosoras/PHPMC7-Multi-Language
