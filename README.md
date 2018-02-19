# Dynamic Web Back-End

This project is a foundation of many other projects which based on a solid application structure.

Important embodied parts of the project commented in the source code (with `NOTE` prefix).

It has the following features;

* Translate thrown exceptions from actions to proper responses,
* Send front-end application (Vue.js, React etc.),
* Authenticate and refresh JWT tokens (without database),

## How to Use

Just clone this project and add your own features. Since this project based on [Lumen](https://lumen.laravel.com/),
PHP and Laravel knowledge is necessary.

**TODO: `.env` dosyasından bahset**

## Deployment

### Shared Hosting

Upload entire project to the server via FTP client. The project has it's own `.htaccess` file for Apache (and LiteSpeed)
server(s) URL rewriting, but **for NGINX you MUST enable URL rewriting**. Please refer to
[this document](https://laravel.com/docs/5.6/deployment#server-configuration) to learn how to use Laravel/Lumen with
NGINX.
