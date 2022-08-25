# Exhibition of photographers of Mathematics and mechanics department of NSU

<hr style="background-color: #54b686; height: 3px">

## Description

This is an educational project with ambition to be actually deployed in the framework of Novosibirsk State University's website. 

It is intended as framework-less application — no major frameworks/libraries are used, other than Bootstrap and Slick for visual features. 
All backend is custom, its structure is inspired by Laravel. 

App uses MVC pattern for separation of concerns:
- **Model**, which includes custom QueryObject-style interface for interaction with MySQL database via PDO and Entity classes for corresponding MySQL tables, can be found in [**~/App/Models**](https://github.com/jekay23/expo/tree/master/App/Models).
- **View**, which includes View-classes that format data for usage in templates and templates of all kinds themselves, can be found in [**~/Resources/Views**](https://github.com/jekay23/expo/tree/master/Resources/Views).
- **Controller**, which includes all the data fetching, API handling, validation etc., can be found in [**~/App/Http/Controllers**](https://github.com/jekay23/expo/tree/master/App/Http/Controllers). 
Routing, which is usually considered to be part of Controller too, is located in [**~/Routes**](https://github.com/jekay23/expo/tree/master/Routes).

This app exploits server-side rendering with a few JavaScript-enabled interactivity features.

Commits have preceding task codes (e.g. T-9.4) for internal use, can be ignored.

Used PHP version: 7.4. 
The code is written in compliance with PSR-12. 
Virtual machine or container with LAMP is recommended 

## How to run
To have a fully-functional application, after cloning this repo you have to:
- create **~/App/Models/DataBaseCredentials.php** with a class that has static method `getCredentials(): array` that has no arguments and returns array of PDO parameters (of string type):
  1. name of host (e.g. 'localhost')
  2. name of database (in MySQL)
  3. name of MySQL user (e.g. 'webapp')
  4. password for this user
- create **~/App/Mail/EmailCredentials.php** with a class that has static method `getEmailCredentials(): array` that has no arguments and returns array of email parameters (of string type):
  1. username for SMTP client
  2. password for this user (search for App Password in your email client)
  3. email that will e displayed in email (should be the same as SMTP username probably)
- create **~/App/Http/Controllers/HashCredentials.php** with a class that has static method `getSalt(string $type): string` which gets one of the following strings as argument: `'password'`, `'id'`, `'filename'`, `'token'`, and yields a salt string (salt must be same for same arguments, should be different for different arguments).

- These files are not here for obvious security concerns. You might also need to create folder **~/Public/tmp** for photo uploading to work correctly.
 
## Admin app for this website

There is a separate admin app written in React + TypeScript. It's stored in a separate repo [expoAdmin](https://github.com/jekay23/expoReact). In current repository there is **~/Public/admin.html** that is only used for the admin app, and also traces of it can be found in **~/Public/.htaccess**, **~/Config/deploy.sh**, CSS and **~/.gitignore**.

If you wish to clone the admin app too, you should create **~/React** and clone the [expoAdmin](https://github.com/jekay23/expoReact) repo there (so that, for example, expoAdmin's README.md is accessible via **~/React/README.md**)

## Plans for future development

Right now my internship, during which I created the app, has successfully finished, so the project is in a little-bit of a limbo-state.
It's not being actively worked on, but there are plans for finding people who will continue its development. 
The project is not yet production-ready. But to get there, there are following obvious proposals:
- implement all components to be dynamic objects, not static classes (like the latest **~/Resources/Views/Components/Like**),
- enhance APIs, make all of them RESTful,
- add lazy-loading capabilities for images,
- work on the issue of being able to quick-like photos without being signed in,
- load only useful JS and CSS for each specific page,
- make more filters and enhance the compilation's UI/UX,
- create dev and test branches if there's ever a new developer on this project...

## Contacts

Should you have any suggestions, please text me in Telegram via [@zhenyayatsko](https://t.me/zhenyayatsko)!