# plizi
Plizi is code of social network writed on Vue.js + Laravel

This code was writed by developers as MVP project. 

We have a lot of work. Worked together but customer **not paid** for this job for anyone of developers for their work it published as open source project.

Developers in developers list can use this repo in CV. 

Anybody alse can try self skills and try to repeat this MVP by design as vue.js-developer, laravel-developer or murkupp layout developer.

## Developers list

[Alexey Bashmakow](https://github.com/targettius) - TeamLead Senior Frontend Web-Developer

[Daniar Akhmetov](https://github.com/dahmetov) - BackLead Senior Backend Web-Developer

[Slava Rudnev](https://github.com/RSol) - Senior Fullstack Web-Developer 

[Yusif Zourab](https://github.com/iLegion) - Middle FullStack Web-Developer

[Bohdan Pronkurov](https://github.com/breez17) - Middle Frontend Web-Developer

[Julia Ivanova](https://github.com/avarosa91) - Middle Frontend Web-Developer

[Sergey Veremiychuk](https://github.com/veremey) - Middle Frontend Web-Developer


### Assets
[Prototype] (https://marvelapp.com/prototype/e17hh7a/screens) screen for social network.

Design you can find in _design_ path.



# Начальная установка Laravel

```
$ composer install
$ php artisan migrate # накатить изменения в стркутуре базы
$ php artisan db:seed # сгенерировать тестового пользователя test@gmail.com/secret
```

проверьте чтобы в .env была переменная JWT_SECRET , если ее нет или пуста

```
$ php artisan jwt:secret


```


## Сокеты

`php artisan ws:serve` - запуск сервера

`php artisan ws:send` - тестовое сообщение


### Пример клиентского кода

```
const conn = new ab.connect('ws://192.168.10.10:8080/pubsub',
    (s) => {
        s.subscribe('onNewData', (topic, data) => {
        console.log(data.data);
    })},
    (code, reason, detail)=> {},
    {
        maxRetries: 10,
        retryDelay: 4000,
        skipSubprotocolCheck: true
    }
);

```


# Установка для Windows

- Установить VirtualBox
- Установить Vagrant
- Установить git bash https://git-scm.com/downloads
- Открыть git bash и выполнить команду `ssh-keygen.exe`
- Выполнить `vagrant box add laravel/homestead`
- Выполнить `git clone https://github.com/laravel/homestead.git ~/Homestead`
- Выполнить `cd ~/Homestead`
- Выполнить `./init.sh`
- Отредактировать Homestead.yml как в примере ниже. Где E:/Projects/plizi.loc путь к проекту.
```yaml
---
ip: "192.168.10.10"
memory: 2048
cpus: 2
provider: virtualbox

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa

folders:
    - map: E:/Projects/plizi.loc
      to: /home/vagrant/plizi.loc

sites:
    - map: plizi.loc
      to: /home/vagrant/plizi.loc/public

databases:
    - plizi

features:
    - mariadb: false
    - ohmyzsh: false
    - webdriver: false
```
- Прописать C:/Windows/System32/drivers/etc/hosts

```
192.168.10.10 plizi.loc
```

- Выполнить `cd ~/Homestead`
- Выполнить `vagrant up`
- Выполнить `vagrant ssh`
- Выполнить `cd plizi.loc`
- Выполнить `sudo apt install php-zmq`
- Выполнить `composer install` 

> Должна быть включена виртуализация в BIOS.

> npm install и npm run dev | build выполнять на хосте, а не внутри виртуальной машины.



# Сборка Vue-приложения
После импорта репозитория сначала нужно установить все нужные для проекта модули **_из папки_** **frontend** (поскольку для нашего фронта она является корневой):

Для этого в консоли выполняем команду

> **`cd front-end`**

И уже тут устанавливаем модули:

> **`npm install`** - установка модулей

Для сборки проекта используем:

> **`npm run prod`** - билд фронтенд приложения для работы в Laravel'ном окружении (используется сборщик Laravel-Mix)

> **`npm run build`** - билд фронтенд приложения (используется сборщик Vue CLI)

Корень front-end'ной части проекта находится в папке **/frontend**

Исходники лежат в папке **/frontend/src**
 
Целевая папка для сборки **/frontend/public**


# Запуск Vue-приложения для разработки

В папке **/frontend/public** есть файл **index.html.example**  

Перед сохраняем его как **index.html** в ту же папку (_он локальный для каждого разработчика, и в репозитарий не входит_).

Открываем свой **index.html** и прописываем нужные настройки.

В переменные **apiURL** и **wsUrl** прописываем URL'ы для доступа к API сайта и к серверу веб-сокетов.

Можно закомментировать эти переменные в разделе **local access** и раскомментировать их в разделе **remote access**

Тогда будет обращение к API и веб-сокетам на удалённом DEV-сервере

При локальном доступе нужно убедиться, что команда **`php artisan ws:serve`** запускает сервер чата на порту, _отличном от **8080**_.

Для этого проверьте переменную **WEBSOCKET_URI** в Вашем локальном **.env** файле (пример в .env.example).

Сделайте все нужные изменения в index.html и запускайте DEV-сервер:

> **`npm run dev`** - запускает DEV-сервер, который сам обновляет содержимое в браузере при изменении исходников

После запуска DEV-сервера **путь к нему копируется в буфер обмена** (_браузер не открывается_).

> **http://localhost:8080/** - по этому адресу работает DEV-сервер


##Работа с папкой frontend  как с отдельным проектом в phpStorm
Если нужно работать только с front-end'ной частью проекта, то с папкой frontend можно работать как с отдельным проектом.   

> Но репозитарий нужно тянуть с сервера весь! 

После того как стянули репозитарий, в корневной папке проекта (**socnet_plizi**) выполняем команды:

> **`git pull origin crossdev`**

> **`git branch --set-upstream-to=origin/crossdev crossdev`**

> **`git pull origin crossdev`**

После этого можно открывать папку **frontend** в phpStorm как проект и пробовать делать там pull средствами Шторма.

# Тесты

Добавить тестовую БД в Homestead.yml и перезапустить vagrant.
```yaml
databases:
    - plizi
    - plizi_test
```
Или добавить патчем
```sql
CREATE DATABASE plizi_test;
```

Запуск тестов
`php artisan test`

# Docker / Docker-compose

Инструкция для установки на Ubuntu - https://docs.docker.com/install/linux/docker-ce/ubuntu/

Инструкция для установки на Windows - https://docs.docker.com/docker-for-windows/

Инструкция для установки на Mac - https://docs.docker.com/docker-for-mac/install/

**`/nginx`** - Конфигурация nginx для контейнера

**`/php`** - Конфигурация php для контейнера

Для запуска контейнеров используйте

`docker-compose up --build`

Для запуска контейнеров в detach mode используйте

`docker-compose up --build -d`

Приложение будет доступно по адресу
`http://localhost:9080`

Если необходимо поменяйте порт для сервиса nginx в файле **`docker-compose.yml`**


