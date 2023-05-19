# Project Title

Verification API for Accredify Technical Assessment

## Table of Contents

- [Project Description](#project-description)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)

## Project Description

Thank you for the opportunity to do this Technical Assessment. This was my first time with Laravel and I am eager to learn more.

I managed to reach all 3 conditions in routes\web.php and was working on incorporating Laravel more in routes\api.php before I ran out of time.
I have included all expectations and assumptions but did not finish all notes. I did not manage to add tests.

## Features

Uses SQLite as database

Files/Directories created or changed in any way:
- app\Http\Controllers\APICall.php
- app\Http\Controllers\Controller.php
- app\Http\Controllers\DatabaseController.php
- app\Http\Middleware\RestrictFileSize.php
- app\Http\Requests\checkRecipientNameEmail.php
- app\Http\Kernel.php
- app\Models
- config\cacert.pem
- database\migrations\2023_05_18_111000_verify_d_b.php
- database\database.sqlite
- public\sample.json
- public\no-recipient-name.json
- routes\api.php
- routes\web.php
- .env


## Installation

Explain how to install and set up your project. Include any dependencies or prerequisites that need to be installed.

```
.env
1. Change the 'DB_DATABASE' attribute in the .env file to the absolute path of 'database/database.sqlite'.

php.ini
2. Change curl.cainfo path in php.ini to match absolute path of 'config\cacert.pem'
3. Enable curl, pdo_sqlite3 and sqlite3 extensions in php.ini
```

## Usage

```bash
To test, change the file path on line 112 in api.php between sample.json (success case) and no-recipient-name.json (fails first condition). 
```

