# Laravel Signed URLs

> Enhanced signed URLs for Laravel, including attaching data, click limits and expiry.

<!-- PROJECT SHIELDS -->

[![Build Status](https://scrutinizer-ci.com/g/linkeys-app/signed-url/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/linkeys-app/signed-url/build-status/develop)
[![Code Coverage](https://scrutinizer-ci.com/g/linkeys-app/signed-url/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/linkeys-app/signed-url/?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linkeys-app/signed-url/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/linkeys-app/signed-url/?branch=develop)

## Table of Contents

* [Overview](#overview)
* [Getting Started](#getting-started)
* [Installation](#installation)
* [Usage](#usage)
* [Examples](#examples)
* [Licence](#license)
* [Contact](#contact)

## Overview

Revamped URL signing brought to Laravel. Pass data through any url securely, and control expiry and number of clicks.

- Securely attach data to links to make it available to your controllers.
- Create links of a limited lifetime.
- Limit the number of clicks allowed on a link.
- Limit the number of clicks on a group of links, such as only allowing one of a group of links to be clicked.

## Getting Started

It couldn't be easier to install and set up url signing in your laravel app.

### Installation

How to install

## Usage

### Standard Link
The easiest way to create a link is through the facade:

```php 
$link = \Linkeys\LinkGenerator\Link::generate('https://www.example.com/invitation');
echo $link; // https://www.example.com/invitation?uuid=UUID
```

The link can now be sent out or used just like normal signed URLs. 

#### Data 
Instead of encoding data into the url yourself, simply pass it as the second argument.

```php 
$link = \Linkeys\LinkGenerator\Link::generate('https://www.example.com/invitation', ['foo' => 'bar']);
echo $link; // https://www.example.com/invitation?uuid=UUID
```
In your controller, e.g. InvitationController.php
```php
echo $request->get('foo');  // bar
```

Through this method, the data can't be altered by anyone and can't even be seen by users, securing your application and hiding implementation details.

#### Expiry

Additional to a basic link is the ability to set the expiry of the link. Only want a link to be available for 24 hours?


```php 
$link = \Linkeys\LinkGenerator\Link::generate('https://www.example.com/invitation', ['foo' => 'bar'], '+24 hours');
```

The expiry accepts a string, unix timestamp or a datetime instance (i.e. Carbon).

#### Click Limit

The number of clicks of a link can also be set. If you only want a user to be able to click a link one time:

```php 
$link = \Linkeys\LinkGenerator\Link::generate('https://www.example.com/invitation', ['foo' => 'bar'], '+24 hours', 1);
```

The first time the link is clicked, the route will work like normal. The second time, since the link only has a single click, an exception will be thrown.
Of course, passing ```null``` instead of ```'+24 hours'``` to the expiry parameter will create links of an indefinite lifetime.

#### Link Groups
By grouping links, the click limit may be spread across multiple links. Given a group with a click limit of 2 but 3 links will only allow two total clicks.
Expiry is default for links unless they specify it themselves.

```php
    $group = \Linkeys\LinkGenerator\Link::group(function($links) {
        $links->generate('https://www.example.com', ['foo'=>'bar']),
        $links->generate('https://www.example.com', ['foo'=>'baz'])
    }, '+ 24 hours', 1)'
```

This will create two links, both with different data and expiring in 24 hours, but since the group click limit is 1 only a single link may be clicked.
This is useful for situations in which you want to give the user a choice of links to click, such as for an invitation (the user should only be able to click 'Yes' or 'No' to respond).

You can access the links using ```$group->links; ```, which wll return a Laravel collection.

#### Error handling

#### Default Views

## Examples

## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.
See `CONTRIBUTING.md` for more information.

1. Fork the Project

2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)

3. Commit your Changes (`git commit -m 'Add some AmazingFeature`)

4. Push to the Branch (`git push origin feature/AmazingFeature`)

5. Open a pull request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Contact

Toby Twigger - [toby@linkeys.app](mailto:toby@linkeys.app)

Project Link: [https://github.com/linkeys-app/signed-url](https://github.com/linkeys-app/signed-url)

