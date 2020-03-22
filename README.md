FE Container
============

+ Microservice must act as a global container where FE can store what they need  to add complete services to order when the flux impose.

Storage configuration
---------------------
+ Add storage configuration, TTL and data storage limit in config\autoload\container.config.php.
+ Data storage limit must be stored in number of bytes
+ TTL must be stored in number of miliseconds, minutes, hours, days or weeks (ex: 60m, 1h etc)

Standalone install
------------------
+ git clone https://github.com/Repkit/eba-fe-container.git
+ cd fe-container
+ composer install
+ composer update

Requests
--------
* **GET** - .../container/[/:token] - adds content to bucket
* **FETCH** - .../container[/:token] - gets the content from bucket
