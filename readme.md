# Introduction

This is a PHP tool to loop-API to translate.googleapi.com to translate key into locale string.

## Requirements

- PHP 7.4
- Composer

## Setup

1. At project root, run `composer install` to get dependencies.

```
composer install
```

2. Copy config.php.sample to config.php and set your Google API key.

```
cp config.php.sample config.php
```

3. Put files in `target` folder (see `Expected target` section)

## Expected target

This tool is designed with Yii2 folder hierarchy in mind, but with some effort, it should fit all.

- {module}
  - {lang}
    - {file.php}

The content of `file.php` should be just plain PHP array, with the key being the text to be translated, and the value should be empty. Values that is non-empty will be treated as if the translation is already done, and the tool will build the output file with the value instead of performing an API call to get the translation text.

You can have as many `{module}` folder, as many `{lang}` folder, and as many `{file.php}` in the `{lang}` folder.

Example hierarchy:

- target
  - backend
    - en
      - user.php
      - security.php
    - id
      - user.php
      - security.php
    - jp
      - user.php
      - security.php
  - common
    - en
      - user.php
      - security.php
    - id
      - user.php
      - security.php
    - zh-CN
      - user.php
      - security.php

## Running the tool

```
php index.php
```

## Output

The tool will generate `{file.php.translated}` file at the same directory it finds the `{file.php}`. The content of the .translated file is key-value array, where key is the original text to be translated, and the value is the translated text.

## Useful links

https://console.cloud.google.com/apis/api/translate.googleapis.com/metrics\
https://console.cloud.google.com/apis/credentials\
