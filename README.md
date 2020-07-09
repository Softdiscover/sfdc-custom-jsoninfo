# CUSTOM WORDPRESS PLUGIN #

### DESCRIPTION ###

Wordpress Plugin that makes available a custom endpoint `/sfdc_show_users`. When visitor navigates to that endpoint `/sfdc_show_users`, a list of users are shown in a table. When visitor clicks on any user info inside the table, a modal window is triggered showing user details inside. 

All user information are taken from a REST API [https://jsonplaceholder.typicode.com/users](https://jsonplaceholder.typicode.com/users)


## Getting started
* [List of Requirements](#user-content-list-of-requirements)
* [Installation](#user-content-installation)
    * [Clone repository](#user-content-1-clone-repository)
    * [Install all dependencies](#user-content-2-install-all-the-necessary-dependencies)
* [Usage](#user-content-usage)
* [Key features](#user-content-key-features)
    * [Custom endpoint](#user-content-1-custom-endpoint)
    * [A table with users' details is visible](#user-content-2-a-table-with-users-details-is-visible)
    * [Unit tests](#user-content-3-unit-tests)
    * [PHPCS checks](#user-content-4-phpcs-checks)
    * [Cache](#user-content-5-cache)

## List of Requirements
* PHP >=7.3 with CURL enabled
* Wordpress >= 5.4.2
* Composer - it adds a Symfony Cache Component which is used to make cache of HTTP requests which are made to REST API endpoint

## Installation
### 1. Clone repository
Within your WordPress installation, navigate to wp-content/plugins and run the following commands:
```bash
git clone https://github.com/Softdiscover/sfdc-custom-jsoninfo.git
cd sfdc-custom-jsoninfo
```
### 2. Install all the necessary dependencies
Navigate to the root of the plugin and then run the following command:  
```bash
composer install  
```
and activate the plugin from Admin panel.

## Usage
Once the plugin is activated, navigate to the endpoint `/sfdc_show_users` and a table with list of users will be loaded. then When visitor clicks on any user detail inside the table, a modal window is triggered showing user details inside.

## Key features
### 1. Custom endpoint

the Custom endpoint is available at `/sfdc_show_users`

### 2. A table with users' details is visible

once the page is loaded, An HTTP request is made via AJAX to API https://jsonplaceholder.typicode.com/users and if there is information, the table is filled with users data.

![img show users](https://i.imgur.com/r2Cl2IX.png)

when visitor click on any user name/username/id in the table, a new HTTP request is made via AJAX to API (e.g. https://jsonplaceholder.typicode.com/users/4). then the user information is printed in the modal window. 

![img get detail](https://media.giphy.com/media/VFG9l6ortDZBoAsGuq/giphy.gif)

### 3. Unit tests

Unit tests run without loading Wordpress nor the external API. For run all tests, just run the following command: 

```bash
composer test
```

![img unit test](https://i.imgur.com/4y80Psg.png)

### 4. PHPCS checks

Code is compliant with InpSyde code style. it can be checked running the following command: 

```bash
composer sniff
```

![img check InpSyde code style](https://i.imgur.com/mNuS8wS.png)

### 5. Cache
I use Symfony Cache component to set up caching on the server side. each HTTP request generates an unique URL then it is stored in the cache pool of Cache Component. so when the visitor navigate again over a user detail, the information is pulled from the Cache. The cache expiration is set to 60 minutes. 

