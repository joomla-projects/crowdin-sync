**NOTE** - This repository has been archived and is no longer supported, the Joomla project has changed its Crowdin integration workflow and maintenance of this tool is no longer necessary

# Crowdin Synchronization Script for Joomla! CMS Repositories

This is a simple command line based script allowing Joomla! CMS repositories to synchronize their translations with Crowdin.

## Requirements

- PHP 5.4+
- Composer
- An established project on Crowdin
- A `crowdin.yaml` file containing the repository configuration

## Installation

To install this project, run the following command:

```sh
composer require joomla/crowdin-sync
```

## Usage

To run this script, simply run the following command:

```sh
vendor/bin/crowdin
```

It requires one of two configuration switches:

- `--download` to download all translations of this project
- `--upload` to update the source files of this project

## Configuration

Several aspects of the script may be configured with switches on the `crowdin` script, including:

- `--crowdin-config` allows you to specify a custom path to your `crowdin.yaml` file; this must be relative to the project's root directory and defaults to `crowdin.yaml` if not specified
- `--crowdin-project` allows you to specify the project name on Crowdin to connect to and defaults to the `project_identifier` value from the `crowdin.yaml` file
- `--crowdin-api-key` allows you to specify the API key to use to connect to Crowdin; if this is not set, the environment variable specified by `api_key_env` in the `crowdin.yaml` file will be used
