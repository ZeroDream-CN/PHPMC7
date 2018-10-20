# <img src="https://i.natfrp.org/3d939a311fe6bf031f25c4eeefda9c39.png" align="right" style="width: 256px">PHPMC-7
开源 | 跨平台 | 分布式 | FTP 支持 | BungeeCord 支持

您还在四处寻找方便实用的 Minecraft 服务器管理器吗？

PHPMC 7 可能是您最好的选择！

> PHPMC 7 是一个功能强大的 Minecraft 服务器管理面板，由 Akkariin 开发。

Gayhub 基佬猫（雾）》

### 简介
嗨，我是<s>超级鸽子王</s> Akkariin，这是我的第一个 Minecraft 服务器管理器项目。

我很早就想开发这个管理器了，所以我找了个时间写出了这个 PHPMC 7 项目。

英文 README 一堆语法错误不要介意……

### 主要有什么功能呢？
> 1. 完整的 Minecraft 服务器管理功能
> 2. 支持跨平台，您可以在 Windows 或 Linux 上运行它，甚至是 Android 手机和树莓派
> 3. 支持多节点和分布式，您可以用它来管理多个服务器
> 4. 拥有 FTP 文件传输功能
> 5. 支持 Docker 容器（需要对启动命令进行修改）
> 6. 您可以用来运行任何命令行能运行的游戏服务器

### 安全性 & 性能
PHPMC 7 不使用 MD5 这种过时的密码储存方式，我们使用更高安全标准的 BCrypt 哈希。

我们使用 Java IO 流进行命令操作和日志读写，并对传输过程进行优化，效率更高，更稳定。

即使可以通过 Docker 容器方式运行服务端，但是我们仍然不建议您将 PHPMC 7 用于商业出租。

PHPMC 7 支持权限细分，几乎任何操作都可以设置独立权限。

### 在线演示 Demo
* 简体中文: https://demo.phpmc.cn/?lang=zh_CN
* 繁體中文: https://demo.phpmc.cn/?lang=zh_TW
* English: https://demo.phpmc.cn/?lang=en_US
* Russian: https://demo.phpmc.cn/?lang=ru_RU

演示服务器用户名和密码均为：`admin`

此 Demo 只有演示功能，没有实际操作功能，不能开服。

Demo 不是最新版本，也不会实时随着版本更新，如欲体验最新的特性和功能，请下载后安装体验。

### 多语言
PHPMC 7 现在已经支持多语言，您可以在设置中指定系统语言，例如 `en_US` 和 `zh_CN`。

如要下载更多语言包或者您想参与翻译，请访问：https://github.com/kasuganosoras/PHPMC7-Multi-Language

### 开源协议
本项目基于 GNU General Public License v3.0 协议开源

您可以在遵守协议的前提下自由修改，分发，传播，以及用于商业行为。
