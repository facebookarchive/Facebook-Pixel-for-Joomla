# Contributing to Facebook Pixel for Joomla
We want to make contributing to this project as easy and transparent as
possible.

## Our Development Process
Clone this repo with the following command:
Run `$ git clone https://github.com/facebookincubator/Facebook-Pixel-for-Joomla.git`.

Initiate the development environment:
1. Install Composer (https://getcomposer.org/download/).
2. Run the command to install the necessary packages: `$ composer install`.

Build the project and create .zip file:
Run `$ vendor/bin/phing`.

## Pull Requests
We actively welcome your pull requests.

1. Fork the repo and create your branch from `master`.
2. If you've added code that should be tested, add tests.
3. If you've changed APIs, update the documentation.
4. Make sure your code lints.
5. If you haven't already, complete the Contributor License Agreement ("CLA").

## Contributor License Agreement ("CLA")
In order to accept your pull request, we need you to submit a CLA. You only need
to do this once to work on any of Facebook's open source projects.

Complete your CLA here: <https://code.facebook.com/cla>.

## Issues
We use GitHub issues to track public bugs. Please ensure your description is
clear and has sufficient instructions to be able to reproduce the issue.

Facebook has a [bounty program](https://www.facebook.com/whitehat/) for the safe
disclosure of security bugs. In those cases, please go through the process
outlined on that page and do not file a public issue.

## Coding Style
File Format
All files contributed to Joomla must be:
  * Stored as ASCII text
  * Use UTF-8 character encoding
  * Be Unix formatted following these rules:
    1. Lines must end only with a line feed (LF).
    2. Line feeds are represented as ordinal 10, octal 012 and hex 0A.
    3. Do not use carriage returns (CR) like Macintosh computers do or the carriage return/line feed combination (CRLF) like Windows computers do.

Spelling
The spelling of words and terms used in code comments and in the naming of class, functions, variables and constant should generally be in accordance with British English rules (en_GB).

Indenting
Tabs for 2 spaces are used for indenting code.

Line Length
There is no maximum limit for line lengths in files, however, a notional value of about 150 characters is recommended to achieve a good level of readability without horizontal scrolling.

See the full coding standards here (https://developer.joomla.org/coding-standards/basic-guidelines.html).

## License
By contributing to Facebook for Joomla, you agree that your contributions
will be licensed under the LICENSE file in the root directory of
this source tree.
